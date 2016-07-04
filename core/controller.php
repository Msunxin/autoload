<?php
/**
 * Created by PhpStorm.
 * User: BZ
 * Date: 2016/6/30
 * Time: 11:21
 */

namespace autoload\core;


class controller{

    public $template = '';
    public $cache = '';

    public $view = array();
    public $display;

    protected $controller;
    protected $method;

    public function before($url)
    {
        $this->_init($url);
    }

    public function after()
    {
        // TODO: Implement __destruct() method.
        $this->_after();
    }

    private function _init($url){
        $this->template = APP_DIR.'/app/skin';
        $this->cache = APP_DIR.'/cache';
        $this->controller = $url['c'];
        $this->method = $url['m'];

        $this->display = $url['c'].DIRECTORY_SEPARATOR.$url['m'].'.html';

    }

    private function _after(){
        $tem = new \autoload\core\MySmarty($this->template, $this->cache);
        $smarty = $tem->Smarty_GuestBook();
        foreach ($this->view as $k=>$v){
            $smarty->assign($k,$v);
        }

        $smarty->display($this->display);
    }
}