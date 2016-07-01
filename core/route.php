<?php
/**
 * Created by PhpStorm.
 * User: BZ
 * Date: 2016/6/29
 * Time: 15:53
 */
namespace autoload\core;

class route{
    protected static $controller = '';
    protected static $method ='';
    protected static $url='';

    protected static $params='';
    public static function getUrl(){
        self::$url = ltrim($_SERVER['REQUEST_URI'],'/');
        return self::delUrl();
    }

    private function delUrl(){
        if(self::$url == '/' || strlen(self::$url) <= 1){
            self::$controller = 'index';
            self::$method = 'index';
        }else{
            $urlArr = explode('/',self::$url);
            if($urlArr[0] == 'index.php') array_shift($urlArr);
            $num = count($urlArr);
            if($num == 0)
            {
                self::$controller = 'index';
                self::$method = 'index';
            }else
            {
                self::$controller = $urlArr[0];
                if(stripos($urlArr[$num-1],'?')){
                    $methodArr = explode('?',$urlArr[$num-1]);
                    if(count($methodArr) == 2){
                        self::$method = $methodArr[0];
                        self::$params = $methodArr[1];
                    }else{
                        self::$method = $methodArr[0];
                        array_shift($methodArr) && self::$params = implode('?',$methodArr );
                    }
                }else{
                    self::$method = $urlArr[1];
                }
            }
        }

        $route = array();
        self::$controller && $route['c'] = self::$controller or die('no controller');
        self::$method && $route['m'] = self::$method or $route['m'] = 'index';
        self::$params && $route['p'] = self::$params;
        return $route;
    }
}