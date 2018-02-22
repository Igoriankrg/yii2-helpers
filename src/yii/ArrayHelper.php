<?php

namespace yii2lab\helpers\yii;

use yii\helpers\ArrayHelper as YiiArrayHelper;

class ArrayHelper extends YiiArrayHelper {

    /**
     * @param array $array
     * @param string|\Closure $key
     * @param mixed $default
     * @return array
     */
    public static function group($array, $key, $default = null)
    {
        $result = [];
        foreach ($array as $k => $element) {
            $result[static::getValue($element, $key, $default)][$k] = $element;
        }

        return $result;
    }

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
		if(empty($condition)) {
			return true;
		}
		$item = self::toArray($item);
		foreach ($condition as $fieldName => $conditionValue) {
			$itemValue = self::getValue($item, $fieldName);
			if(self::isEqual($itemValue, $conditionValue)) {
				return true;
			}
		}
		return false;
	}
	
	private static function isEqual($itemValue, $conditionValue) {
		if(empty($itemValue) && empty($conditionValue)) {
			return true;
		}
		if(!empty($itemValue) && $itemValue == $conditionValue) {
			return true;
		}
		return false;
	}
}
