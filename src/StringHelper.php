<?php

namespace yii2lab\helpers;

class StringHelper {
	
	const PATTERN_SPACES = '#\s+#m';
	
	public static function setTab($content, $tabCount) {
		$content = str_replace(str_repeat(SPC, $tabCount), TAB, $content);
		return $content;
	}
	
	public static function search($haystack, $needle, $offset = 0) {
		$needle = self::prepareTextForSearch($needle);
		$haystack = self::prepareTextForSearch($haystack);
		if(empty($needle) || empty($haystack)) {
			return false;
		}
		$isExists = mb_strpos($haystack, $needle, $offset) !== false;
		return $isExists;
	}
	
	private static function prepareTextForSearch($text) {
		$text = self::extractWords($text);
		$text= mb_strtolower($text);
		$text = self::removeAllSpace($text);
		return $text;
	}
	
	public static function getWordArray($content) {
		$content = self::extractWords($content);
		return self::textToArray($content);
	}
	
	public static function getWordRate($content) {
		$content = mb_strtolower($content);
		$wordArray = self::getWordArray($content);
		$result = [];
		foreach($wordArray as $word) {
			if(!is_numeric($word) && mb_strlen($word) > 1) {
				$result[$word] = isset($result[$word]) ? $result[$word] + 1 : 1;
			}
		}
		arsort($result);
		return $result;
	}
	
	public static function textToLine($text) {
		$text = preg_replace(self::PATTERN_SPACES, SPC, $text);
		return $text;
	}
	
	public static function removeDoubleSpace($text) {
		$text = preg_replace(self::PATTERN_SPACES, SPC, $text);
		return $text;
	}
	
	public static function removeAllSpace($text) {
		$text = preg_replace(self::PATTERN_SPACES, EMP, $text);
		return $text;
	}
	
	public static function textToArray($text) {
		$text = self::removeDoubleSpace($text);
		return explode(SPC, $text);
	}
	
	public static function mask($value, $length = 2, $valueLength = null) {
		if(empty($value)) {
			return EMP;
		}
		$begin = substr($value, 0, $length);
		$end = substr($value, 0 - $length);
		$valueLength = !empty($valueLength) ? $valueLength : strlen($value) - $length * 2;
		return $begin . str_repeat('*', $valueLength) . $end;
	}
	
	private static function extractWords($text) {
		$text = preg_replace('/[^0-9A-Za-zА-Яа-яЁё]/iu', SPC, $text);
		$text = self::removeDoubleSpace($text);
		$text = trim($text);
		return $text;
	}
	
}