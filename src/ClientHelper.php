<?php

namespace yii2lab\helpers;

use Yii;

class ClientHelper
{

	const IP_HEADER_KEY = 'ip_address';
	
    public static function ip() {
	    return '127.0.0.1';
    	if (APP == CONSOLE) {
            return '127.0.0.1';
        }
       /* if ($_SERVER['REMOTE_ADDR'] == env('servers.nat.address') && isset($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIp = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }*/
	    $clientIp = Yii::$app->request->userIP;
	    $headerIp = Yii::$app->request->headers->get(self::IP_HEADER_KEY, false);
	    if($headerIp) {
		    $clientIp = $headerIp;
	    }
        return $clientIp;
    }

}
