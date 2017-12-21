<?php

namespace yii2lab\helpers\generator;

use yii\helpers\ArrayHelper;

class EnumGeneratorHelper {
	
	private static $defaultConfig = [
		'use' => ['yii2lab\misc\enums\BaseEnum'],
		'afterClassName' => 'extends BaseEnum',
	];
	
	public static function generate($config) {
		$config = ArrayHelper::merge($config, self::$defaultConfig);
		ClassGeneratorHelper::generate($config);
	}
	
}
