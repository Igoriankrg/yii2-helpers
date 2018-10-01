<?php

namespace yii2lab\helpers;

class PhpHelper {
	
	public static function isCallable($value) {
		return $value instanceof \Closure || is_callable($value);
	}
	
	public static function runValue($value, $params = []) {
		if(self::isCallable($value)) {
			$value = call_user_func_array($value, $params);
		}
		return $value;
	}
	
	public static function isValidName($name) {
		if(!is_string($name)) {
			return false;
		}
		// todo: /^[\w]{1}[\w\d_]+$/i
		return preg_match('/([a-zA-Z0-9_]+)/', $name);
	}
	
}