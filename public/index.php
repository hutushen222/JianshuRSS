<?php

require '../bootstrap.php';

// Prepare app

$app = new \Slim\Slim(require '../config.php');

// Define routes

$app->get('/', function () use ($app) {

    $app->render('home.tpl.php', array(
        'action' => $app->urlFor('home'),
        'rootUri' => $app->request()->getRootUri(),
    ));

})->name('home');

$app->post('/', function () use ($app) {
    $url = trim($app->request()->post('url'));

    if ($url && str_start_with(JIANSHU, $url)) {
        if (str_start_with(JIANSHU_RECOMMENDATIONS_NOTES, $url)) {
            $app->redirect($app->urlFor('feeds.recommendations'));
        } elseif (str_start_with(JIANSHU_ALL_NOTES, $url) || str_start_with(JIANSHU_TIMELINE_NOTES, $url)) {
            $app->redirect($app->urlFor('feeds.latest'));
        } elseif (str_start_with(JIANSHU_COLLECTIONS_ROOT, $url)) {
            $app->redirect($app->urlFor('feeds.collections', array('id' => substr($url, strlen(JIANSHU_COLLECTIONS_ROOT)))));
        } elseif(str_start_with(JIANSHU_NOTEBOOKS_ROOT, $url)) {
            $id = substr($url, strlen(JIANSHU_NOTEBOOKS_ROOT));
            if (($pos = strpos($id, '/')) !== false) {
                $id = substr($id, 0, $pos);
            }

            $app->redirect($app->urlFor('feeds.notebooks', array('id' => $id)));
        } elseif (str_start_with(JIANSHU_USERS_ROOT, $url)) {
            $id = substr($url, strlen(JIANSHU_USERS_ROOT));
            if (($pos = strpos($id, '/')) !== false) {
                $id = substr($id, 0, $pos);
            }

            $app->redirect($app->urlFor('feeds.users', array('id' => $id)));
        } else {
            throw new Exception('Invalid Jianshu URL.');
        }
    } else {
        $app->redirect($app->urlFor('feeds.recommendations'));
    }
});

$app->get('/about', function () use ($app) {

    echo 'About Jianshu RSS!';

})->name('pages.about');

$app->get('/feeds/latest/notes', function () use ($app) {

    // List
    $latest_notes_file_path = CACHE_ROOT . DS . 'latest-notes.html';
    if (file_exists($latest_notes_file_path) && filemtime($latest_notes_file_path) > time() - 900) {
        $html_str = file_get_contents($latest_notes_file_path);
    } else {
        $html_str = file_get_contents(JIANSHU_ALL_NOTES);
        if ($html_str === false) {
            throw new Exception('Can not fetch Jianshu note\'s content with uri: ' . JIANSHU_ALL_NOTES);
        }
        file_put_contents($latest_notes_file_path, $html_str);
    }

    $html = str_get_html($html_str);
    $meta = array(
        'title' => '简书 • 最新文章',
        'link' => JIANSHU_TIMELINE_NOTES,
        'description' => '简书是一款属于写作者的笔记本, 我们致力于提供一个简洁而优雅的环境让你专注于书写。',
    );

    $notes = array();
    $i = 0;
    foreach ($html->find('ul.all-list li') as $article) {
        $note = new stdClass();

        $title = $article->find('h4 a', 0);
        $note->title = trim($title->plaintext);
        $note->uri = $title->href;

        $notebook = $article->find('.article-info a', 1);
        $note->book = trim($notebook->plaintext);
        $note->book_uri = $notebook->href;

        $notes[] = $note;

        if (++$i >= 10) break;
    }

    // Notes
    foreach ($notes as $note) {
        fetchNote($note);
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
        $item = $item->title($note->title . ' by ' . $note->author . ' · ' . $note->book)
                     ->url(JIANSHU . $note->uri)
                     ->description($note->body)
                     ->pubDate(strtotime($note->created))
                     ->appendTo($channel);
    }

    $res = $app->response();
    $res->status(200);
    $res->header('Content-Type', 'application/rss+xml; charset=utf-8');
    $res->header('X-Powered-By', 'Slim');
    $res->body($feed->render());

    return $res;
})->name('feeds.latest');

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
        'title' => '编辑推荐',
        'link' => JIANSHU_RECOMMENDATIONS_NOTES,
        'description' => '简书是一款属于写作者的笔记本, 我们致力于提供一个简洁而优雅的环境让你专注于书写。',
    );

    $notes = array();
    foreach ($html->find('.thumbnails li .article') as $article) {
        $note = new stdClass();

        $title = $article->find('a.title', 0);
        $note->title = trim($title->plaintext);
        $note->uri = $title->href;

        $notebook = $article->find('a.notebook', 0);
        $note->book = trim($notebook->plaintext);
        $note->book_uri = $notebook->href;

        $notes[] = $note;
    }

    // Notes
    foreach ($notes as $note) {
        fetchNote($note);
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
        $item = $item->title($note->title . ' by ' . $note->author . ' · ' . $note->book)
            ->url(JIANSHU . $note->uri)
            ->description($note->body)
            ->pubDate(strtotime($note->created))
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
    $html_str = file_get_contents(JIANSHU_COLLECTIONS_ROOT . $id);
    $html = str_get_html($html_str);

    $meta = array(
        'title' => trim($html->find('.aside .title', 0)->plaintext),
        'link' => JIANSHU_COLLECTIONS_ROOT . $id,
        'description' => trim($html->find('.aside .description', 0)->plaintext),
    );

    $notes = array();
    foreach ($html->find('.thumbnails li') as $article) {
        $note = new stdClass();

        $title = $article->find('h4 a', 0);
        $note->title = trim($title->plaintext);
        $note->uri = $title->href;

        $notebook = $article->find('a.notebook', 0);
        $note->book = trim($notebook->plaintext);
        $note->book_uri = $notebook->href;

        $notes[] = $note;
    }

    // Notes
    foreach ($notes as $note) {
        fetchNote($note);
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
        $item = $item->title($note->title . ' by ' . $note->author . ' · ' . $note->book)
            ->url(JIANSHU . $note->uri)
            ->description($note->body)
            ->pubDate(strtotime($note->created))
            ->appendTo($channel);
    }

    $res = $app->response();
    $res->status(200);
    $res->header('Content-Type', 'application/rss+xml; charset=utf-8');
    $res->header('X-Powered-By', 'Slim');
    $res->body($feed->render());

    return $res;
})->name('feeds.collections')
    ->conditions(array('id' => '[a-zA-Z0-9]*'));

$app->get('/feeds/notebooks/:id', function ($id) use ($app) {
    $html_str = file_get_contents(JIANSHU_NOTEBOOKS_ROOT . $id . '/latest');
    $html = str_get_html($html_str);

    $meta = array(
        'title' => trim($html->find('.aside .title', 0)->plaintext),
        'link' => JIANSHU_NOTEBOOKS_ROOT . $id . '/latest',
        'description' => 'by ' . trim($html->find('.aside .author a', 1)->plaintext),
    );



    $notes = array();
    foreach ($html->find('.thumbnails li') as $element) {
        $note = new stdClass();
        $note->uri = $element->find('h4 a', 0)->href;
        $note->title = trim($element->find('h4', 0)->plaintext);

        $note->book = $meta['title'];
        $note->book_uri = $meta['link'];

        $notes[] = $note;
    }

    // Notes
    foreach ($notes as $note) {
        fetchNote($note);
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
        $item = $item->title($note->title . ' by ' . $note->author . ' · ' . $note->book)
            ->url(JIANSHU . $note->uri)
            ->description($note->body)
            ->pubDate(strtotime($note->created))
            ->appendTo($channel);
    }

    $res = $app->response();
    $res->status(200);
    $res->header('Content-Type', 'application/rss+xml; charset=utf-8');
    $res->header('X-Powered-By', 'Slim');
    $res->body($feed->render());

    return $res;
})->name('feeds.notebooks')
    ->conditions(array('id' => '[1-9][0-9]*'));

$app->get('/feeds/users/:id', function ($id) use ($app) {
    $html_str = file_get_contents(JIANSHU_USERS_ROOT . $id);
    $html = str_get_html($html_str);

    $meta = array(
        'title' => trim($html->find('.people .basic-info h3 a', 0)->plaintext),
        'link' => JIANSHU_USERS_ROOT . $id,
        'description' => trim($html->find('.people .about .intro', 0)->plaintext),
    );

    $notes = array();
    foreach ($html->find('.recent-post .latest-notes li') as $article) {
        $note = new stdClass();

        $title = $article->find('.title', 0);
        $note->uri = $title->href;
        $note->title = trim($title->plaintext);

        $notebook = $article->find('a.notebook', 0);
        $note->book = trim($notebook->plaintext);
        $note->book_uri = $notebook->href;

        $notes[] = $note;
    }

    // Notes
    foreach ($notes as $note) {
        fetchNote($note);
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
        $item = $item->title($note->title . ' by ' . $note->author . ' · ' . $note->book)
            ->url(JIANSHU . $note->uri)
            ->description($note->body)
            ->pubDate(strtotime($note->created))
            ->appendTo($channel);
    }

    $res = $app->response();
    $res->status(200);
    $res->header('Content-Type', 'application/rss+xml; charset=utf-8');
    $res->header('X-Powered-By', 'Slim');
    $res->body($feed->render());

    return $res;

})->name('feeds.users')
    ->conditions(array('id' => '[a-zA-Z0-9]*'));

// Run app
$app->run();
