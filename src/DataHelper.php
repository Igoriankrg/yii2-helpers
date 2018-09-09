<?php

namespace yii2lab\helpers;

use yii2lab\extension\yii\helpers\FileHelper;

class DataHelper {
	
	const EXT = 'php';
	const ALIAS_FIXTURES = '@common/fixtures/data';
	const ALIAS_DATA = '@common/data';
	
	public static function loadFixture($name, $alias = self::ALIAS_FIXTURES) {
		return self::load($name, $alias);
	}
	
	public static function loadData($name, $alias = self::ALIAS_DATA) {
		return self::load($name, $alias);
	}
	
	private static function load($name, $alias) {
		$path = FileHelper::getAlias($alias);
		$path = rtrim($path, '\\/');
		$file = $name . DOT . self::EXT;
		return include($path . DS . $file);
	}
	
}
