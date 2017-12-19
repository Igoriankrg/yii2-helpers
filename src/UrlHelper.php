<?php

namespace yii2lab\helpers;

use yii\helpers\Url;

class UrlHelper {
	
	public static function isAbsolute($url) {
		$pattern = "/^(?:ftp|https?|feed)?:?\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*
        (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:
        (?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?]
        (?:[\w#!:\.\?\+\|=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/xi";
		return (bool) preg_match($pattern, $url);
	}
	
	public static function generateUrl($url, $getParameters = null) {
			$url = Url::to([$url]);
		if(!empty($getParameters)) {
			$get = self::generateGetParameters($getParameters);
			if(!empty($get)) {
				$url .= '?' . $get;
			}
		}
		return $url;
	}
	
	public static function generateGetParameters($params) {
		$result = '';
		foreach($params as $name => $value) {
			$result .= "&$name=$value";
		}
		$result = trim($result, '&');
		return $result;
	}
	
}
