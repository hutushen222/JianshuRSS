<?php

require 'vendor/autoload.php';
require 'library/simple_html_dom.php';
require 'library/functions.php';

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__);

define('CACHE_ROOT', ROOT . DS . 'storage' . DS . 'cache');
define('NOTES_ROOT', ROOT . DS . 'storage' . DS . 'notes');


define('JIANSHU', 'http://jianshu.io');
define('JIANSHU_RECOMMENDATIONS_NOTES', 'http://jianshu.io/recommendations/notes');
define('JIANSHU_COLLECTIONS_ROOT', 'http://jianshu.io/c/');
define('JIANSHU_NOTEBOOKS_ROOT', 'http://jianshu.io/notebooks/');
define('JIANSHU_USERS_ROOT', 'http://jianshu.io/users/');