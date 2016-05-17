
<?php

class Controller_Common_Help extends Controller_Base {

    /**
     * @ApiDescription(section="Help")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/api/Help/index")
     */
    public function action_index() {
        $zdurl = $this->data('zdurl');
        $result = $this ->_dealUrl($zdurl);
        $this->result = [
            'result' => $result
        ];
    }
    public static function filter_index() {
        return  ['zdurl' => S::url()->prepare()];
    }
    
    private function _dealUrl($zdurl) {
        if (Kohana::$environment === Kohana::DEVELOPMENT) {
            if ($zdurl){
                $params = parse_url(str_replace('\\', '/',$zdurl));
                $params = explode('/', $params['path']);
                $param = array_values(array_filter($params));
                //param5个值，0为host，1为api，2为dir，3为controller,4为action
                if (count($param) == 4) {
                    $param[4] = "Index";
                } else if (count($param) == 3) {
                    $param[3] = "Index";
                    $param[4] = "Index";
                } else if(count($param) != 5) {
                    return 'The url is not correct ';
                }
                $param[2] =  ucfirst($param[2]);
                $param[3] =  ucfirst($param[3]);
$php = <<<EOF
<?php

class Controller_{$param[2]}_{$param[3]} extends Controller_Base {
    public function action_index() {

    }
}
EOF;

$method = <<<EOF
    public function action_{$param['4']}() {

    }
}
EOF;
                $Directory = DOCROOT.'modules/'.$param[2].'/classes/Controller/'.$param[2];
                $fileName = DOCROOT.'modules/'.$param[2].'/classes/Controller/'.$param[2].'/'.$param[3].EXT;
                if( !is_file($fileName) && is_dir($Directory) ){
                    return file_put_contents($fileName, $php);
                }else if( !is_dir($Directory) ){
                    return 'Directory does not exist ';
                }else if( is_file($fileName) ){
                    include_once $fileName;
                    $ref = new ReflectionClass("Controller_{$param[2]}_{$param[3]}");
                    if($ref->hasMethod("action_$param[4]")){
                        return "Method the existing ";
                    }else{
                        //$str = rtrim(chop(file_get_contents($fileName)),'}');
                        preg_match('/([^}]*)$/i', file_get_contents($fileName),$matches);
                        $str = preg_replace('/(.*)\}{1}([^}]*)$/i','$1',file_get_contents($fileName));
                        return file_put_contents($fileName,$str.$method.$matches[0]);
                    }
                }else{
                    return "error";
                }
            }else{
                return 'url is empty';
            }
        }
        return false;
    }
}
