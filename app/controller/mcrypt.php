<?php
/**
 * mcrypt加密解密类
 */
namespace autoload\app\controller;

use autoload\core\controller AS A;

class mcrypt extends A{
    private static function getKey(){
        return "sdn?saf-fgj=%b3$1cgfd6*--fdsgc567780";
    }
    public static function encrypt($value){
        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
        $key = substr(self::getKey(), 0, mcrypt_enc_get_key_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $ret = base64_encode(mcrypt_generic($td, $value));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }
    public static function dencrypt($value){
        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
        $key = substr(self::getKey(), 0, mcrypt_enc_get_key_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $ret = trim(mdecrypt_generic($td, base64_decode($value))) ;
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }

    public function index(){
        $result = $this->encrypt(222222);
        var_dump($result);die;
    }

    public function get(){
        $res = $this->dencrypt('sOJg78gHb7Q=');
        var_dump($res);die;
    }
}