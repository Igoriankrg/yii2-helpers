<?php

namespace yii2lab\helpers;

class StringHelper {
	
	const PATTERN_SPACES = '#\s+#m';
	
	public static function genUuid() {
		$uuid = array(
			'time_low'  => 0,
			'time_mid'  => 0,
			'time_hi'  => 0,
			'clock_seq_hi' => 0,
			'clock_seq_low' => 0,
			'node'   => array()
		);
		
		$uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
		$uuid['time_mid'] = mt_rand(0, 0xffff);
		$uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
		$uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
		$uuid['clock_seq_low'] = mt_rand(0, 255);
		
		for ($i = 0; $i < 6; $i++) {
			$uuid['node'][$i] = mt_rand(0, 255);
		}
		
		$uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
			$uuid['time_low'],
			$uuid['time_mid'],
			$uuid['time_hi'],
			$uuid['clock_seq_hi'],
			$uuid['clock_seq_low'],
			$uuid['node'][0],
			$uuid['node'][1],
			$uuid['node'][2],
			$uuid['node'][3],
			$uuid['node'][4],
			$uuid['node'][5]
		);
		
		return $uuid;
	}
	
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
    
    public static function isSha1($string) {
		return preg_match('/[0-9a-f]{40}/i', $string);
    }
}