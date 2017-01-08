<?php

require '../bootstrap.php';

// Prepare app

app($app = new \Slim\Slim(require '../config.php'));

// Define routes

$app->get('/', function () use ($app) {

    $app->render('home.tpl.php', array(
        'action' => $app->urlFor('home'),
        'rootUri' => $app->request()->getRootUri(),
    ));

})->name('home');

$app->post('/', function () use ($app) {
    $url = trim($app->request()->post('url'));

    if ($url && str_start_with(JIANSHU_ROOT, $url)) {
        if (str_start_with(JIANSHU_RECOMMENDATIONS_ROOT, $url)) {
            $app->redirect($app->urlFor('feeds.recommendations'));
        } elseif (str_start_with(JIANSHU_TRENDING_WEEKLY_ROOT, $url)) {
            $app->redirect($app->urlFor('feeds.trending.weekly'));
        } elseif (str_start_with(JIANSHU_TRENDING_MONTHLY_ROOT, $url)) {
            $app->redirect($app->urlFor('feeds.trending.monthly'));
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
            $app->redirect($app->urlFor('feeds.latest'));
        }
    } else {
        $app->redirect($app->urlFor('feeds.homepage'));
    }
});

$app->get('/about', function () use ($app) {

    echo 'About Jianshu RSS!';

})->name('pages.about');

$app->get('/feeds/homepage', function () use ($app) {
    return feed_response(notes_feed(fetch_homepage_notes()));
})->name('feeds.homepage');

$app->get('/feeds/latest/notes', function () use ($app) {
    return feed_response(notes_feed(fetch_homepage_notes()));
})->name('feeds.latest');

// 推荐 日报
$app->get('/feeds/recommendations/notes/daily', function () {
    return feed_response(notes_feed(fetch_recommendation_notes(60)));
})->name('feeds.recommendations.daily');

// 推荐 新上榜
$app->get('/feeds/recommendations/notes/latest', function () use ($app) {
    return feed_response(notes_feed(fetch_recommendation_notes(56)));
})->name('feeds.recommendations.latest');

$app->get('/feeds/recommendations/notes', function () {
    return feed_response(notes_feed(fetch_recommendation_notes(56)));
})->name('feeds.recommendations');

$app->get('/feeds/trending/weekly', function () {
    return feed_response(notes_feed(fetch_trending_notes('weekly')));
})->name('feeds.trending.weekly');

$app->get('/feeds/trending/monthly', function () {
    return feed_response(notes_feed(fetch_trending_notes('monthly')));
})->name('feeds.trending.monthly');

$app->get('/feeds/collections/:id', function ($id) {
    return feed_response(notes_feed(fetch_collection_notes($id)));
})->name('feeds.collections')
    ->conditions(array('id' => '[a-zA-Z0-9]*'));

$app->get('/feeds/notebooks/:id', function ($id) {
    return feed_response(notes_feed(fetch_notebook_notes($id)));
})->name('feeds.notebooks')
    ->conditions(array('id' => '[1-9][0-9]*'));

$app->get('/feeds/users/:id', function ($id) use ($app) {
    return feed_response(notes_feed(fetch_user_notes($id)));
})->name('feeds.users')
    ->conditions(array('id' => '[a-zA-Z0-9]*'));

// Run app
$app->run();
