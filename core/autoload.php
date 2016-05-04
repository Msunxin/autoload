<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/27
 * Time: 16:12
 */

class autoload{

    protected $prefix = array();


    public function register(){
        spl_autoload_register(array($this,'load'));
    }


    private function load($class){
        $prefix = $class;
        if(false !== $len = stripos($prefix,'\\')){
            $prefix = substr($class,0,$len+1);
            $name = substr($class,$len+1);
            $map_file = $this->loadNamespaceFile($prefix,$name);
            if($map_file){
                return $map_file;
            }
        }
        return false;

    }

    /**
     * @param $prefix   命名空间
     * @param $name     实际类名
     * @param null $prepend     是否优先
     */
    public  function addNamespace($prefix,$name,$prepend=null){
            $prefix = trim($prefix,'\\').'\\';      //处理格式
            if(isset($prefix) !== false){
                $this->prefix[$prefix] = array();
            }
            if($prepend){
                array_unshift($this->prefix[$prefix],$name);
            }else{
                array_push($this->prefix[$prefix],$name);
            }
    }

    /**
     * @param $prefix
     * @param $name
     * @return bool|string
     */
    private function loadNamespaceFile($prefix,$name){
        if(isset($this->prefix[$prefix]) === false){
            return false;
        }
        foreach($this->prefix[$prefix] as $v){
            $file = $v
                    . str_replace('\\',DIRECTORY_SEPARATOR , $name)
                    . '.php';
            if($this->requireFile($file)){
                return $file;
            }
        }
        return false;
    }

    private function requireFile($file){
        if(file_exists($file)){
            require("{$file}");
            
        }else{
            return false;
        }
    }
}