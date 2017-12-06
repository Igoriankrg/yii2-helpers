<?php

namespace yii2lab\helpers\generator;

use yii\helpers\ArrayHelper;

class EnumGeneratorHelper {
	
	private static $defaultConfig = [
		'use' => ['yii2lab\misc\enums\BaseEnum'],
		'afterClassName' => 'extends BaseEnum',
	];
	
	public static function generateClass($config) {
		$config = ArrayHelper::merge($config, self::$defaultConfig);
		ClassGeneratorHelper::generateClass($config);
	}
	
}
