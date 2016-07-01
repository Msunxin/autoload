<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/27
 * Time: 17:23
 */
namespace autoload\app\controller;

use autoload\core\controller AS A;
class controller extends A{
    public function ceshi(){
        $redis = new \Redis();
        $res = $redis->connect('192.168.43.128',6379);
        //var_dump($res);die;
        var_dump($redis->lrange('111',0,10));
        //$redis->lPush('111','sunx');
        var_dump($redis->llen('111'));
    }
}