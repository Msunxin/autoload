<?php
/**
 * Created by PhpStorm.
 * User: BZ
 * Date: 2016/6/29
 * Time: 19:33
 */

namespace autoload\app\controller;
use autoload\core\controller as A;

class index extends A{
    public function index(){
        $this->view = array(1,2,4);
        
    }
}