<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;

class ModuleHelper
{
	
	public static function isActiveUrl($urlList) {
		$urlList = ArrayHelper::toArray($urlList);
		foreach($urlList as $url) {
			if(self::isActiveUrlItem($url)) {
				return true;
			}
		}
		return false;
	}
	
	private static function isActiveUrlItem($url) {
		$url = trim($url, SL);
		$urlParts = explode(SL, $url);
		$urlParts = array_slice($urlParts,0, 3);
		
		$currentParts[] = Yii::$app->controller->module->id;
		$currentParts[] = Yii::$app->controller->id;
		$currentParts[] = Yii::$app->controller->action->id;
		foreach($urlParts as $k => $part) {
			if($currentParts[$k] != $part) {
				return false;
			}
		}
		return true;
	}
	
	public static function has($name, $app = null) {
		$config = self::getConfig($name, $app);
		return !empty($config);
	}
	
	public static function allByApp($app = null) {
		$modules = self::loadConfigFromApp($app);
		return $modules;
	}
	
	public static function getConfig($name, $app = null) {
		if(!empty($app) && $app != APP) {
			$modules = self::loadConfigFromApp($app);
			return ArrayHelper::getValue($modules, $name);
		}
		$key = 'modules.' . $name;
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
		$main = @include($appPath . DS . 'config' . DS . 'modules.php');
		$local = @include($appPath . DS . 'config' . DS . 'modules-local.php');
		return ArrayHelper::merge($main ?: [], $local ?: []);
	}
}
