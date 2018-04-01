<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\FileHelper;

class TempHelper {
	
	public static function fullName($name) {
		$fullName = self::basePath() . DS . $name;
		$fullName = FileHelper::normalizePath($fullName);
		$directory = FileHelper::up($fullName);
		FileHelper::createDirectory($directory);
		return $fullName;
	}
	
	public static function basePath() {
		return Yii::getAlias('@runtime/temp');
	}
	
	public static function clearAll() {
		FileHelper::removeDirectory(self::basePath());
	}
	
}
