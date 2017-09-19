<?php

namespace yii2lab\helpers;

class Registry {
	
	private static $data = [];
	
	static function get($key = null, $default = null) {
		if(empty($key)) {
			return self::$data;
		}
		if(array_key_exists($key, self::$data)) {
			return self::$data[$key];
		} else {
			return $default;
		}
	}
	
	static function has($key) {
		if(empty($key)) {
			return false;
		}
		if(array_key_exists($key, self::$data)) {
			return true;
		} else {
			return false;
		}
	}
	
	static function set($key, $value) {
		if(!empty($key)) {
			self::$data[$key] = $value;
		}
	}
	
	static function remove($key) {
		if(!empty($key) && array_key_exists($key, self::$data)) {
			unset(self::$data[$key]);
		}
	}
	
	protected function __construct() {
	
	}
	
}
