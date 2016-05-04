<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/27
 * Time: 17:23
 */
namespace autoload\app\controller;

class controller{
    public function ceshi(){
        $redis = new \Redis();
        $redis->connect('192.168.249.129',6379);
        var_dump($redis->lrange('111',0,10));
        //$redis->lPush('111','sunx');
        var_dump($redis->llen('111'));
    }
}