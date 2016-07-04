<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/27
 * Time: 17:22
 */
define('APP_DIR',__DIR__);
define('CONFIG',APP_DIR.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'config');
define('APP_DEBUG',FALSE);
require "core/route.php";

require 'core/autoload.php';
$obj = new autoload();
$realPath = realPath(dirname('./'));
$obj->addNamespace('autoload', $realPath.DIRECTORY_SEPARATOR);
$obj->register();

$url = autoload\core\route::getUrl();
autoload\core\core::run($url);
