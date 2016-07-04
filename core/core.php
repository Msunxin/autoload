<?php
/**
 * Created by PhpStorm.
 * User: BZ
 * Date: 2016/6/29
 * Time: 19:44
 */
namespace autoload\core;
class core{

    public static function initApp(){
        self::errorLog();//错误日志

        self::import(['composer'=>'composer']);//引用第三方库
    }

    public static function run($url){
        $name = "autoload\\app\\controller\\" . $url['c'] ;
        if(class_exists($name)){
            $re =new \ReflectionClass($name);
            $hasMethod = $re -> hasMethod($url['m']);
            $m = new $name();
            try{
                if(!$hasMethod){
                    throw new \Exception('this method is not defined');
                }else{
//                    $beginTime = microtime(true);
                    $m -> before($url);
                    $m -> $url['m']();
                    $m -> after();
//                    $endTime = microtime(true);
//                    echo $endTime-$beginTime;
                }
            }catch (\Exception $e){
                echo 'ERROR message :'.$e->getMessage()
                    .'<br/>ERROR file : '.$e->getFile()
                    .'<br/>ERROR line : '.$e->getLine();
            }

        }else{
            $ex = new \Exception('ERROR message: this controller is not defined');
            echo $ex->getMessage();
        }

    }

    /**
     * @param $module
     * @param string $path
     */
    public static function import($module, $path = 'public'){
        if(is_array($module) && count($module) > 0){
            foreach ($module as $v){
                require_once $path.'/vendor/autoload.php';
            }
        }
    }

    /**
     * 生成错误目录
     */
    public static function errorLog(){
        if(defined('APP_DIR') && !is_dir(APP_DIR.'\log')){
             mkdir(APP_DIR.'\log');
        }
    }

    public static function getConfig($choose='default'){
        if(!defined('CONFIG')) return false;
        $config = require CONFIG.DIRECTORY_SEPARATOR.'config.php';
        return $config[$choose];
    }
}