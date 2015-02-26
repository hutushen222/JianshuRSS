<?php

require 'vendor/autoload.php';
require 'library/simple_html_dom.php';
require 'library/functions.php';

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__);

define('CACHE_ROOT', ROOT . DS . 'storage' . DS . 'cache');
define('NOTES_ROOT', ROOT . DS . 'storage' . DS . 'notes');


define('JIANSHU', 'http://www.jianshu.com');
define('JIANSHU_ALL_NOTES', 'http://www.jianshu.com/all/notes');
define('JIANSHU_TIMELINE_NOTES', 'http://www.jianshu.com/timeline/latest');
define('JIANSHU_RECOMMENDATIONS_NOTES', 'http://www.jianshu.com/recommendations/notes');
define('JIANSHU_COLLECTIONS_ROOT', 'http://www.jianshu.com/collection/');
define('JIANSHU_NOTEBOOKS_ROOT', 'http://www.jianshu.com/notebooks/');
define('JIANSHU_USERS_ROOT', 'http://www.jianshu.com/users/');
