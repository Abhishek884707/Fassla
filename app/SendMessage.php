<?php
namespace App;
use Nexmo\Laravel\Facades\Nexmo;

    class SendMessage{
        public static function sendmessage($phone,$content){
            // $mess = $request->mess;
            $nexmo = app('Nexmo\Client');

            $result = $nexmo->message()->send([
                'to'   => '91'.(int)$phone,
                'from' => 'Fassla',
                'text' => $content
            ]);
        }
    }

?>
