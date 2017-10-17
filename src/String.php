<?php

namespace yii2lab\helpers;

class String {

	static function mask($value, $length = 2, $valueLength = null) {
		if(empty($value)) {
			return '';
		}
		$begin = substr($value, 0, $length);
		$end = substr($value, 0 - $length);
		$valueLength = !empty($valueLength) ? $valueLength : strlen($value) - $length * 2;
		return $begin . str_repeat('*', $valueLength) . $end;
	}

}