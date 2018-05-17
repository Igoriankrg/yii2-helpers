<?php

namespace yii2lab\helpers;

use Yii;

class ClientHelper
{

	const IP_HEADER_KEY = 'ip_address';
	
    public static function ip() {
        if (APP == CONSOLE) {
            return '127.0.0.1';
        }
	    if (APP == API) {
		    $headerIp = Yii::$app->request->headers->get(self::IP_HEADER_KEY, false);
		    if($headerIp) {
			    return $headerIp;
		    }
	    }
        if ($_SERVER['REMOTE_ADDR'] == env('servers.nat.address') && isset($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIp = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIp;
    }

}
