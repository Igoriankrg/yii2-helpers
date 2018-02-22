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

    static function generateRandomString($length = 8,$set=null,$set_characters=null,$hight_quality=false) {
        if(empty($set) && empty($set_characters)) {
            $set = 'num|lower|upper';
        }
        $characters = '';
        $arr = explode('|',$set);
        if(in_array('num',$arr)) {
            $characters .= '0123456789';
        }
        if(in_array('lower',$arr)) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if(in_array('upper',$arr)) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if(in_array('char',$arr)) {
            $characters .= '~!@#$^&*`_-=*/+%!?.,:;\'"\\|{}[]<>()';
        }
        if(!empty($set_characters)) {
            $characters .= $set_characters;
        }
        $randstring = '';
        if($hight_quality) {
            $char_arr = array();
            $characters_len = mb_strlen($characters,'utf-8');
        }
        for($i = 0; $i < $length; $i++) {
            $r = mt_rand(0,strlen($characters)-1);
            if($hight_quality) {
                if(in_array($r,$char_arr)) {
                    while(in_array($r,$char_arr)) {
                        $r = mt_rand(0,strlen($characters)-1);
                    }
                }
                $char_arr[] = $r;
                if(count($char_arr) >= $characters_len) {
                    $char_arr = array();
                }
            }
            $randstring .= $characters[$r];
        }
        return $randstring;
    }
}