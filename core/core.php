<?php
/**
 * Created by PhpStorm.
 * User: BZ
 * Date: 2016/6/29
 * Time: 19:44
 */
namespace autoload\core;
class core{

    public static function run($url){
        $name = "autoload\\app\\controller\\" . $url['c'] ;
        $m = new $name();
        $m->$url['m']();
    }

    public static function import($module, $path = 'public'){
        if(is_array($module) && count($module) > 0){
            foreach ($module as $v){
                require_once $path.'/vendor/autoload.php';
            }
        }
    }
}