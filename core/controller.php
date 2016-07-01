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

    public $view;
    public static $tem = 'index.html';

    public function __construct()
    {
        $this->_init();
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->_after();
    }

    private function _init(){
        $this->template = APP_DIR.'/app/skin';
        $this->cache = APP_DIR.'/cache';

    }

    private function _after(){
        $tem = new \autoload\core\MySmarty($this->template, $this->cache);
        $smarty = $tem->Smarty_GuestBook();
        $smarty->assign('view',$this->view);
        $smarty->display(self::$tem);
    }
}