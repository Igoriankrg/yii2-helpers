<?php

namespace yii2lab\helpers;

use function GuzzleHttp\Psr7\parse_query;
use yii\helpers\Url;

class UrlHelper {
	
	public static function parse($url) {
		$r = parse_url($url);
		$r['query'] = parse_query($r['query']);
		return $r;
	}
	
	public static function currentDomain() {
		return self::extractDomainFromUrl($_SERVER['HTTP_HOST']);
	}
	
	public static function baseDomain($domain) {
		$arr = explode(DOT, $domain);
		while(count($arr) > 2) {
			array_shift($arr);
		}
		return implode(DOT, $arr);
	}
	
	public static function extractDomainFromUrl($url) {
		$domainArr = explode(":", $url);
		$domain = count($domainArr) > 1 ? $domainArr[1] : $domainArr[0];
		$segmentArr = explode(":", $domain);
		$domain = trim($segmentArr[0], '/');
		return $domain;
	}
	
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
