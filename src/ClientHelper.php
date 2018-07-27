<?php

namespace yii2lab\helpers;

use InvalidArgumentException;
use Yii;
use yii2lab\domain\data\GetParams;
use xj\ua\UserAgent;

class ClientHelper
{

	const IP_HEADER_KEY = 'ip_address';
	const LOCALHOST_IP = '127.0.0.1';
	
	public static function getAgentInfo($isLowerCase = false) {
		try {
			$userAgent = UserAgent::model();
		} catch(InvalidArgumentException $e) {
			return [];
		}
		
		/* @var \xj\ua\UserAgent $userAgent */
		$uaAttributes =  $userAgent->getAttributes();
		if($isLowerCase) {
			foreach($uaAttributes as &$attribute) {
				$attribute = strtolower($attribute);
			}
		}
		return $uaAttributes;
	}
	
	public static function getQueryFromRequest($queryParams = null) {
		if($queryParams === null) {
			$queryParams = Yii::$app->request->get();
		}
		$getParams = new GetParams();
		return $getParams->getAllParams($queryParams);
	}
	
    public static function ip() {
    	if (self::isConsole()) {
            return self::LOCALHOST_IP;
        }
        $ip = self::getIpFromHeader();
    	if($ip) {
    		return $ip;
	    }
	    $ip = self::getIpFromRequest();
	    if($ip) {
		    return $ip;
	    }
    }
	
	private static function getIpFromHeader() {
		if (self::isConsole()) {
			return self::LOCALHOST_IP;
		}
		$ip = Yii::$app->request->headers->get(self::IP_HEADER_KEY, false);
		return $ip;
	}
 
	public static function getIpFromRequest() {
		if (self::isConsole()) {
			return self::LOCALHOST_IP;
		}
		/* if ($_SERVER['REMOTE_ADDR'] == env('servers.nat.address') && isset($_SERVER['HTTP_CLIENT_IP'])) {
			 $clientIp = $_SERVER['HTTP_CLIENT_IP'];
		 } else {
			 $clientIp = $_SERVER['REMOTE_ADDR'];
		 }*/
		return Yii::$app->request->userIP;
	}
 
	private static function isConsole() {
		// todo: костыль
		return true;
		return APP == CONSOLE;
	}
}
