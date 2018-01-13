<?php

namespace yii2lab\helpers;

class StringHelper {

	static function textToLine($text) {
		$text = preg_replace('#\s+#m', SPC, $text);
		return $text;
	}
	
	static function removeDoubleSpace($text) {
		$text = preg_replace('# +#m', SPC, $text);
		return $text;
	}
	
	static function textToArray($text) {
		$text = self::removeDoubleSpace($text);
		return explode(SPC, $text);
	}
	
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