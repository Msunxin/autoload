<?php
namespace autoload\app\controller;

use autoload\core\controller AS A;

class active extends A{
    public function in()
    {
        try {
            $link = stomp_connect ('tcp://192.168.43.128:61613','sunx',"sunx");
        } catch(StompException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        /* close connection */

        $a = time();
        $re = stomp_send($link, 'ce.shi', (string)$a, array("persistent" => "true",'Type'=>'ABC11'));
        var_dump($re);die;
    }

    public function out()
    {
        $queue  = 'ce.shi';

        try {
            $stomp = new \Stomp('tcp://192.168.43.128:61613','sunx',"sunx");
            $stomp->subscribe($queue);
            $a = 0;
            while ($a <= 10) {
                if ($stomp->hasFrame()) {
                    $frame = $stomp->readFrame();
                    if ($frame != NULL) {
                        print "Received: " . $frame->body . " - time now is  " . date("Y-m-d H:i:s"). "<br>";
                        $stomp->ack($frame);
                    }
//       sleep(1);
                }
                else {
                    print "No frames to read<br>";
                }
                $a++;
            }
        } catch(StompException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
        die;
    }
}