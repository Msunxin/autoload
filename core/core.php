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
        if(class_exists($name)){
            $re =new \ReflectionClass($name);
            $hasMethod = $re -> hasMethod($url['m']);
            $m = new $name();
            try{
                if(!$hasMethod){
                    throw new \Exception('this method is not define');
                }else{
                    $m -> $url['m']();
                    //\controller()::$tem = $url['c'];
                }
            }catch (\Exception $e){
                echo 'ERROR message :'.$e->getMessage()
                    .'<br/>ERROR file : '.$e->getFile()
                    .'<br/>ERROR line : '.$e->getLine();
            }

        }else{
            $ex = new \Exception('ERROR message: this controller is not define');
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
}