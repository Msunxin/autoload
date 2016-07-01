<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/27
 * Time: 17:22
 */
define('APP_DIR',__DIR__);
define('APP_DEBUG',FALSE);

require "core/route.php";

require 'core/autoload.php';
$obj = new autoload();
$realPath = realPath(dirname('./'));
$obj->addNamespace('autoload', $realPath.DIRECTORY_SEPARATOR);
$obj->register();
use autoload\core as core;

core\core::errorLog();//错误日志

core\core::import(['composer'=>'composer']);//引用第三方库

$url = core\route::getUrl();
core\core::run($url);
