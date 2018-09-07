<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\helpers\yii\FileHelper;

class Helper {
	
	public static function parseParams($path, $delimiter, $subDelimiter) {
		$isHasParams = strpos($path, $delimiter);
		if(!$isHasParams) {
			return null;
		}
		$params = [];
		$segments = explode($delimiter, $path);
		foreach($segments as $segment) {
			$s = explode($subDelimiter, $segment);
			if(count($s) > 1) {
				$params[$s[0]] = $s[1];
			} else {
				$params[] = $s[0];
			}
		}
		return $params;
	}
	
	public static function getNotEmptyValue() {
		$args = func_get_args();
		foreach($args as $arg) {
			if(!empty($arg)) {
				return $arg;
			}
		}
		return null;
	}
	
    public static function loadData($name, $key = null) {
        $file = COMMON_DATA_DIR . DS . $name . '.php';
        $data = FileHelper::loadData($file, $key, []);
        return $data;
    }

	public static function isEnabledComponent($config) {
		if(!is_array($config)) {
			return $config;
		}
		$isEnabled = !isset($config['isEnabled']) || !empty($config['isEnabled']);
		unset($config['isEnabled']);
		if(!$isEnabled) {
			return null;
		}
		return $config;
	}
	
	public static function assignAttributesForList($configList, $attributes = null) {
		$configList = ClassHelper::normalizeComponentListConfig($configList);
		foreach($configList as &$item) {
			foreach($attributes as $attributeName => $attributeValue) {
				$item[$attributeName] = $attributeValue;
			}
		}
		return $configList;
	}

	static function getBundlePath($path) {
		if(empty($path)) {
			return false;
		}
		$alias = FileHelper::normalizeAlias($path);
		$dir = Yii::getAlias($alias);
		if(!is_dir($dir)) {
			return false;
		}
		return $alias;
	}
	
	static function getCurrentDbDriver() {
        $driver = EnvService::getConnection('main.driver');
		return  $driver;
	}

	static function getDbConfig($name = null, $isEnvTest = YII_ENV_TEST)
	{
		$configName = $isEnvTest ? 'test' : 'main';
		$config = env("db.$configName");
		if($name) {
			return $config[$name];
		} else {
			return $config;
		}
	}

	/*static function strToArray($value) {
		$value = trim($value, '{}');
		$value = explode(',', $value);
		return $value;
	}*/
	
	static function timeForApi($value, $default = null, $mask = 'Y-m-d\TH:i:s\Z') {
		if(APP != API) {
			return $value;
		}
		if(empty($value)) {
			return $default;
		}
		if(is_numeric($value)) {
			$value = date('Y-m-d H:i:s', $value);
		}
		$datetime = new \DateTime($value);
		$value = $datetime->format($mask);
		return $value;
	}

}