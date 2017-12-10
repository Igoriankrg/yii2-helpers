<?php

namespace yii2lab\helpers;

use yii2lab\helpers\yii\ArrayHelper;

class ModuleHelper
{
	
	static function has($name) {
		$config = self::getConfig($name);
		return !empty($config);
	}
	
	static function getConfig($name) {
		$key = 'modules.' . $name;
		return config($key);
	}
	
	static function getClass($name) {
		$config = self::getConfig($name);
		$moduleClass = is_array($config) ? $config['class'] : $config;
		return $moduleClass;
	}
	
	public static function allNamesByApp($app) {
		$dir = ROOT_DIR . DS . $app . '/modules';
		if( ! is_dir($dir)) {
			return [];
		}
		$modules = scandir($dir);
		ArrayHelper::removeByValue('.', $modules);
		ArrayHelper::removeByValue('..', $modules);
		return $modules;
	}
}
