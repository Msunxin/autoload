<?php
/**
 * Created by PhpStorm.
 * User: BZ
 * Date: 2016/7/1
 * Time: 12:33
 */


namespace autoload\core;
class mySmarty{

    public $template = '';
    public $cache = '';

    public function __construct($tem,$cache){
        $this->template = $tem;
        $this->cache = $cache;
    }

    public function Smarty_GuestBook() {

        // Class Constructor. These automatically get set with each new instance.
        //类构造函数.创建实例的时候自动配置

        $smarty = new \Smarty();

        $smarty->template_dir = $this->template;
        $smarty->compile_dir = $this->cache.'/templates_c/';
        $smarty->cache_dir = $this->cache;

        $smarty->caching = true;
        return $smarty;
    }
}