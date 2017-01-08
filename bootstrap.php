<?php

require 'vendor/autoload.php';
require 'library/simple_html_dom.php';
require 'library/functions.php';

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__);

define('CACHE_ROOT', ROOT . DS . 'storage' . DS . 'cache');
define('NOTES_ROOT', ROOT . DS . 'storage' . DS . 'notes');


// 首页
define('JIANSHU_ROOT', 'http://www.jianshu.com');

// 推荐的前缀
define('JIANSHU_RECOMMENDATIONS_ROOT', 'http://www.jianshu.com/recommendations/notes');

// 热门的前缀
define('JIANSHU_TRENDING_WEEKLY_ROOT', 'http://www.jianshu.com/trending/weekly');
define('JIANSHU_TRENDING_MONTHLY_ROOT', 'http://www.jianshu.com/trending/monthly');

// 专题的前缀
define('JIANSHU_COLLECTIONS_ROOT', 'http://www.jianshu.com/c/');

// 文集的前缀
define('JIANSHU_NOTEBOOKS_ROOT', 'http://www.jianshu.com/nb/');

// 作者的前缀
define('JIANSHU_USERS_ROOT', 'http://www.jianshu.com/u/');
