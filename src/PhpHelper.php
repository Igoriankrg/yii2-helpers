<?php

namespace yii2lab\helpers;

class PhpHelper {
	
	public static function isValidName($name) {
		if(!is_string($name)) {
			return false;
		}
		return preg_match('/([a-zA-Z0-9_]+)/', $name);
	}
	
}