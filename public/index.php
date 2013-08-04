<?php

require '../bootstrap.php';

// Prepare app

$app = new \Slim\Slim(require '../config.php');

// Define routes

$app->get('/', function () use ($app) {

    echo 'Jianshu RSS!';

})->name('home');

$app->post('/', function () use ($app) {

});

$app->get('/about', function () use ($app) {

    echo 'About Jianshu RSS!';

})->name('pages.about');

$app->get('/feeds/recommendations/notes', function () use ($app) {

    // List
    $recommendations_notes_file_path = CACHE_ROOT . DS . 'recommendations-notes.html';
    if (file_exists($recommendations_notes_file_path) && filemtime($recommendations_notes_file_path) > time() - 900) {
        $html_str = file_get_contents($recommendations_notes_file_path);
    } else {
        $html_str = file_get_contents(JIANSHU_RECOMMENDATIONS_NOTES);
        if ($html_str === false) {
            throw new Exception('Can not fetch Jianshu note\'s content with uri: ' . JIANSHU_RECOMMENDATIONS_NOTES);
        }
        file_put_contents($recommendations_notes_file_path, $html_str);
    }

    $html = str_get_html($html_str);
    $meta = array(
        'title' => $html->find('.page-title', 0)->plaintext,
        'link' => JIANSHU_RECOMMENDATIONS_NOTES,
        'description' => '简书是一款属于写作者的笔记本, 我们致力于提供一个简洁而优雅的环境让你专注于书写。',
    );
    $notes = array();
    foreach ($html->find('.thumbnail') as $element) {
        $note = array();
        $note['uri'] =  $element->href;
        $note['title'] = trim($element->find('h4', 0)->plaintext);
        $note['book'] = trim($element->find('.icon-book', 0)->parent()->plaintext);
        $note['book'] = array_pop(explode(' ', $note['book']));
        $note['author'] = trim($element->find('h5', 0)->plaintext);
        $note['avatar'] = trim($element->find('.avatar img', 0)->src);
        $note['created'] = trim($element->find('.icon-time', 0)->parent()->plaintext);
        $note['summary'] = trim($element->find('p', 0)->plaintext);

        $notes[] = (object) $note;
    }

    // Notes
    foreach ($notes as $note) {
        $note_file_path = NOTES_ROOT . DS . md5($note->uri) . '.html';
        if (file_exists($note_file_path)) {
            $html_str = file_get_contents($note_file_path);
        } else {
            $html_str = file_get_contents(JIANSHU . $note->uri);
            if ($html_str === false) {
                throw new Exception('Can not fetch Jianshu note\'s content with uri: ' . $note->uri);
            }
            file_put_contents($note_file_path, $html_str);
        }

        $html = str_get_html($html_str);
        $note->book_uri = $html->find('.article .article-info a', 0)->href;
        $note->author_uri = $html->find('.people .author', 0)->href;
        $note->body = $html->find('.show-content', 0)->innertext;
    }

    // Feed
    $feed = new \Suin\RSSWriter\Feed();
    $channel = new \Suin\RSSWriter\Channel();
    $channel->title($meta['title'])
        ->url($meta['link'])
        ->description($meta['description'])
        ->appendTo($feed);
    foreach ($notes as $note) {
        $item = new \Suin\RSSWriter\Item();
        $item = $item->title($note->title)
            ->url(JIANSHU . $note->uri)
            ->pubDate(strtotime($note->created))
            ->description($note->body)
            ->appendTo($channel);
    }

    $res = $app->response();
    $res->status(200);
    $res->header('Content-Type', 'application/rss+xml; charset=utf-8');
    $res->header('X-Powered-By', 'Slim');
    $res->body($feed->render());

    return $res;
})->name('feeds.recommendations');

$app->get('/feeds/collections/:id', function ($id) use ($app) {

})->name('feeds.collections');

$app->get('/feeds/notebooks/:id', function ($id) use ($app) {

})->name('feeds.notebooks');

$app->get('/feeds/users/:id', function ($id) use ($app) {

})->name('feeds.users');

// Run app
$app->run();