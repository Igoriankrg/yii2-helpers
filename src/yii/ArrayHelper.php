<?php

namespace yii2lab\helpers\yii;

use yii\helpers\ArrayHelper as YiiArrayHelper;

class ArrayHelper extends YiiArrayHelper {
	
	static function inArrayKey($value, $array, $default = null)
	{
		if(!array_key_exists($value, $array)) {
			if(func_num_args() > 2) {
				$value = $default;
			} else {
				$value = key($array);
			}
		}
		return $value;
	}
	
	static function removeByValue($value, &$array) {
		$key = array_search($value,$array);
		if($key !== FALSE) {
			unset($array[$key]);
		}
	}
	
	static function recursiveIterator(array $array, $callback) { //����������� ����� �������
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$array[ $key ] = self::recursiveIterator($value, $callback);
			} else {
				$array[ $key ] = call_user_func($callback, $value);//$callback($value);
			}
		}
		return $array;
	}
	
	public static function findAll(&$array, $condition)
	{
		$all = [];
		foreach ($array as $item) {
			if(self::runCondition($item, $condition)) {
				$all[] = $item;
			}
		}
		return $all;
	}
	
	public static function findOne(&$array, $condition)
	{
		foreach ($array as $item) {
			if(self::runCondition($item, $condition)) {
				return $item;
			}
		}
	}
	
	private static function runCondition($item, $condition)
	{
		$item = self::toArray($item);
		$result = true;
		if(empty($condition)) {
			return true;
		}
		foreach ($condition as $key => $value) {
			if($item[$key] != $value) {
				$result = false;
			}
		}
		return $result;
	}
	
}
