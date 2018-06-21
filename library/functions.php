<?php

/**
 * @param \Slim\Slim|null $app
 *
 * @return \Slim\Slim
 * @throws \Exception
 */
function app($app = null)
{
    static $inner;

    if (!is_null($app)) {
        $inner = $app;
    }

    if (is_null($inner)) {
        throw new Exception('$app is not initialized.');
    }

    return $inner;
}

function dd(...$params)
{
    dump($params);
    die;
}

function str_start_with($needle, $haystack)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

function str_end_with($needle, $haystack)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, - $length) === $needle);
}

/**
 * @param $feed
 *
 * @return \Slim\Http\Response
 */
function feed_response($feed): \Slim\Http\Response
{
    $response = app()->response();
    $response->status(200);
    $response->header('Content-Type', 'application/rss+xml; charset=utf-8');
    $response->header('X-Powered-By', 'Slim');
    $response->body($feed->render());

    return $response;
}

function notes_feed(\JianshuRss\Collection $collection)
{
    $feed = new \Suin\RSSWriter\Feed();

    $channel = new \Suin\RSSWriter\Channel();
    $channel->title($collection->getTitle())
        ->url($collection->getLink())
        ->description($collection->getDescription())
        ->appendTo($feed);

    /** @var \JianshuRss\Note $note */
    foreach ($collection->getNotes() as $note) {
        $item = new \Suin\RSSWriter\Item();
        $item->title($note->getTitle() . ' by ' . $note->getAuthor() . ' · ' . $note->getNotebook())
            ->url($note->getLink())
            ->description($note->getDescription())
            ->pubDate(strtotime($note->getPublishedAt()))
            ->appendTo($channel);
    }

    return $feed;
}

function cache_file_path($filename)
{
    $dailyDir = CACHE_ROOT . DS . date('Ymd');

    if (!file_exists($dailyDir)) {
        mkdir($dailyDir, 0777);
    }

    return $dailyDir . DS . $filename;
}

function html_dom($url, $ttl = 900)
{
    if (str_start_with('//', $url)) {
        $url = 'https:' . $url;
    }

    $cacheFilePath = cache_file_path(md5($url) . '.html');

    if (file_exists($cacheFilePath) && filemtime($cacheFilePath) > time() - $ttl) {
        $html = file_get_contents($cacheFilePath);
    } else {
        $html = file_get_contents($url);

        if ($html === false) {
            throw new Exception('Can not fetch Jianshu note\'s content with url: ' . $url);
        }

        file_put_contents($cacheFilePath, $html);
    }

    if ($html === false) {
        throw new Exception('Can not fetch Jianshu note\'s content with uri: ' . JIANSHU_ROOT);
    }

    return str_get_html($html);
}

function extract_note_uris($htmlDom)
{
    $noteUris = [];
    foreach ($htmlDom->find('ul.note-list li') as $articleDom) {
        $noteUris[] = $articleDom->find('a.title', 0)->href;
    }

    return $noteUris;
}

function fetch_homepage_notes()
{
    $htmlDom = html_dom(JIANSHU_ROOT);

    $notes = new \JianshuRss\Collection();
    $notes->setTitle('简书 • 首页')
        ->setLink(JIANSHU_ROOT)
        ->setDescription('交流故事，沟通想法')
        ->setNotes(fetch_notes_with_uris(extract_note_uris($htmlDom)));

    return $notes;
}

function fetch_trending_notes($type)
{
    if ($type == 'weekly') {
        $title = '简书 • 7日热门';
        $url = JIANSHU_TRENDING_WEEKLY_ROOT;
    } elseif ($type == 'monthly') {
        $title = '简书 • 30日热门';
        $url = JIANSHU_TRENDING_MONTHLY_ROOT;
    } else {
        throw new Exception('Unsupported trending type: ' . $type);
    }

    $htmlDom = html_dom($url);

    $notes = new \JianshuRss\Collection();
    $notes->setTitle($title)
        ->setLink($url)
        ->setDescription('交流故事，沟通想法')
        ->setNotes(fetch_notes_with_uris(extract_note_uris($htmlDom)));

    return $notes;
}

function fetch_recommendation_notes($id = null)
{
    $url = JIANSHU_RECOMMENDATIONS_ROOT . '?category_id=' . $id ?? 56;

    $htmlDom = html_dom($url);

    $notes = new \JianshuRss\Collection();
    $notes->setTitle('简书 • 推荐 • ' . trim(str_replace('- 简书', '', $htmlDom->find('title', 0)->plaintext)))
        ->setLink($url)
        ->setDescription('交流故事，沟通想法')
        ->setNotes(fetch_notes_with_uris(extract_note_uris($htmlDom)));

    return $notes;
}

function fetch_collection_notes($id)
{
    $url = JIANSHU_COLLECTIONS_ROOT . $id;
    $htmlDom = html_dom($url);

    $notes = new \JianshuRss\Collection();
    $notes->setTitle('简书 • 专题 • ' . trim($htmlDom->find('.main-top .title a.name', 0)->plaintext))
        ->setLink($url)
        ->setDescription('交流故事，沟通想法')
        ->setNotes(fetch_notes_with_uris(extract_note_uris($htmlDom)));

    return $notes;
}

function fetch_user_notes($id)
{
    $url = JIANSHU_USERS_ROOT . $id;
    $htmlDom = html_dom($url);

    $notes = new \JianshuRss\Collection();
    $notes->setTitle('简书 • 作者 • ' . trim($htmlDom->find('.main-top .title a.name', 0)->plaintext))
        ->setLink($url)
        ->setDescription('交流故事，沟通想法')
        ->setNotes(fetch_notes_with_uris(extract_note_uris($htmlDom)));

    return $notes;
}

function fetch_notebook_notes($id)
{
    $url = JIANSHU_NOTEBOOKS_ROOT . $id;
    $htmlDom = html_dom($url);

    $notes = new \JianshuRss\Collection();
    $notes->setTitle('简书 • 文集 • ' . trim($htmlDom->find('.main-top .title a.name', 0)->plaintext))
        ->setLink($url)
        ->setDescription('交流故事，沟通想法')
        ->setNotes(fetch_notes_with_uris(extract_note_uris($htmlDom)));

    return $notes;
}

function fetch_notes_with_uris($uris)
{
    $notes = [];

    foreach ($uris as $uri) {
        $notes[] = fetch_note_with_uri($uri);
    }

    return $notes;
}

function fetch_note_with_uri($uri)
{
    return fetch_note_with_url(JIANSHU_ROOT . $uri);
}

function fetch_notes_with_urls($urls)
{
    $notes = [];

    foreach ($urls as $url) {
        $notes[] = fetch_note_with_url($url);
    }

    return $notes;
}

function fetch_note_with_url($url)
{
    $htmlDom = html_dom($url);

    $note = new \JianshuRss\Note();
    $note->setAuthor(trim($htmlDom->find('.author .info .name a', 0)->plaintext))
        ->setNotebook(trim($htmlDom->find('a.notebook', 0)->plaintext))
        ->setPublishedAt(trim(strtr($htmlDom->find('.article .publish-time', 0)->plaintext, '.', '-')))
        ->setTitle(trim($htmlDom->find('.article h1.title', 0)->plaintext))
        ->setLink($url)
        ->setDescription(trim($htmlDom->find('.article .show-content', 0)->innertext));

    return $note;
}


