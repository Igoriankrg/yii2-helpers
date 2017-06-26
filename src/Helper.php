<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\helpers\yii\FileHelper;

class Helper {

	static function getCurrentDbDriver() {
		$dsn = config("components.db.dsn");
		$driver = explode(':', $dsn)[0];
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
	
	static function strToArray($value) {
		$value = trim($value, '{}');
		$value = explode(',', $value);
		return $value;
	}
	
	static function timeForApi($value, $default = null) {
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
		$value = $datetime->format('Y-m-d\TH:i:s\Z');
		return $value;
	}
	
	public static function getApps() {
		return [COMMON, FRONTEND, BACKEND, API, CONSOLE];
	}
	
	public static function getWebApps() {
		return [COMMON, FRONTEND, BACKEND, API];
	}
	
	public static function getApiVersionList()
	{
		$dir = Yii::getAlias('@api');
		$dirList = FileHelper::scanDir($dir);
		$result = [];
		foreach($dirList as $path) {
			if (preg_match('#v([0-9]+)#i', $path)) {
				$result[] = $path;
			}
		}
		return $result;
	}
	
	public static function getApiSubApps()
	{
		$subApps = self::getApiVersionList();
		$result = [];
		foreach($subApps as $app) {
			$result[] = API . '/' . $app;
		}
		return $result;
	}
	
	public static function getModules($app) {
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