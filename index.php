<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/27
 * Time: 17:22
 */
include "core/route.php";

include('core/autoload.php');
$obj = new autoload();
$realPath = realPath(dirname('./'));
$obj->addNamespace('autoload', $realPath.DIRECTORY_SEPARATOR);
$obj->register();
$url = autoload\core\route::getUrl();
autoload\core\core::run($url);