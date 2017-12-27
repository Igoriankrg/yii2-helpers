<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\helpers\yii\FileHelper;

class ModuleHelper
{
	
	public static function has($name, $app = null) {
		$config = self::getConfig($name, $app);
		return !empty($config);
	}
	
	public static function allByApp($app = null) {
		$modules = self::loadConfigFromApp($app);
		return $modules['modules'];
	}
	
	public static function getConfig($name, $app = null) {
		$key = 'modules.' . $name;
		if(!empty($app) && $app != APP) {
			$modules = self::loadConfigFromApp($app);
			return ArrayHelper::getValue($modules, $key);
		}
		return config($key);
	}
	
	public static function getClass($name) {
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
	
	public static function messagesAlias($bundleName) {
		$moduleClass = self::getClass($bundleName);
		if(!class_exists($moduleClass)) {
			return null;
		}
		if(property_exists($moduleClass, 'langDir') && !empty($moduleClass::$langDir)) {
			return $moduleClass::$langDir;
		}
		$path = Helper::getNamespace($moduleClass);
		if(empty($path)) {
			return null;
		}
		return Helper::getBundlePath($path . SL . 'messages');
	}
	
	private static function loadConfigFromApp($app) {
		$appPath = Yii::getAlias('@' . $app);
		$main = include($appPath . DS . 'config' . DS . 'modules.php');
		$local = include($appPath . DS . 'config' . DS . 'modules-local.php');
		return ArrayHelper::merge($main, $local);
	}
}
