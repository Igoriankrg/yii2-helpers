<?php

namespace yii2lab\helpers;

use yii2mod\helpers\ArrayHelper;

abstract class BaseRegistry {
	
	static function get($key = null, $default = null) {
		if(empty($key)) {
			return static::$data;
		}
		return ArrayHelper::getValue(static::$data, $key, $default);
	}
	
	static function has($key) {
		if(empty($key)) {
			return false;
		}
		return ArrayHelper::has(static::$data, $key);
	}
	
	static function set($key, $value) {
		if(!empty($key)) {
			ArrayHelper::set(static::$data, $key, $value);
		}
	}
	
	static function remove($key) {
		if(!empty($key) && array_key_exists($key, static::$data)) {
			ArrayHelper::remove(static::$data, $key);
		}
	}
	
	protected function __construct() {}
	
}
