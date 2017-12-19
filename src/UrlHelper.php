<?php

namespace yii2lab\helpers;

use yii\helpers\Url;

class UrlHelper {
	
	static function isAbsolute($url) {
		return preg_match('#(https?:)#', $url);
	}
	
	static function generateUrl($url, $getParameters = null) {
		$url = Url::to([$url]);
		if(!empty($getParameters)) {
			$get = self::generateGetParameters($getParameters);
			if(!empty($get)) {
				$url .= '?' . $get;
			}
		}
		return $url;
	}
	
	static function generateGetParameters($params) {
		$result = '';
		foreach($params as $name => $value) {
			$result .= "&$name=$value";
		}
		$result = trim($result, '&');
		return $result;
	}
	
}
