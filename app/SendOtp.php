<?php
namespace App;
use Nexmo\Laravel\Facades\Nexmo;

    class SendOtp{
        public static function sendOtp($phone){
            // $mess = $request->mess;
            $code = rand(1111,9999);
            $nexmo = app('Nexmo\Client');

            $result = $nexmo->message()->send([
                'to'   => '91'.(int)$phone,
                'from' => 'Fassla',
                'text' => "Your One time password for authentication is.  ". $code
            ]);

            return $code;

        }
    }

?>
