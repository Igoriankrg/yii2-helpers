<?php

namespace yii2lab\helpers;

use Yii;
use yii\web\UploadedFile;
use yii2lab\helpers\yii\FileHelper;

class TempHelper {
	
	const TEMP_ALIAS = '@runtime/temp';
	
	public static function remove($name) {
		$fileName = self::fullName($name);
		FileHelper::remove($fileName);
	}
	
	public static function save($name, $content) {
		$fileName = self::fullName($name);
		FileHelper::save($fileName, $content);
	}
	
	public static function fullName($name) {
		$fileName = self::basePath($name);
		self::createDirectoryForFile($fileName);
		return $fileName;
	}
	
	public static function createDirectoryForFile($fileName) {
		$directory = FileHelper::up($fileName);
		FileHelper::createDirectory($directory);
	}
	
	public static function copyUploadedToTemp(UploadedFile $uploaded) {
		$tempFile = self::fullName($uploaded->name);
		$uploaded->saveAs($tempFile);
		return $tempFile;
	}
	
	public static function basePath($path = null) {
		$basePath = Yii::getAlias(self::TEMP_ALIAS) . DS . YII_BEGIN_TIME;
		if($path) {
			$basePath .= DS . $path;
		}
		$basePath = FileHelper::normalizePath($basePath);
		return $basePath;
	}
	
	public static function clearAll() {
		FileHelper::removeDirectory(self::basePath());
	}
	
}
