<?php

namespace yii2lab\helpers\generator;

class EnumGeneratorHelper {
	
	public static function generateClass($className, $constList) {
		ClassGeneratorHelper::generateClass([
			'className' => $className,
			'const' => $constList,
			'use' => ['yii2lab\misc\enums\BaseEnum'],
			'afterClassName' => 'extends BaseEnum',
			'doc' => [
				'Этот класс был сгенерирован автоматически.',
				'Не вносите в данный файл изменения, они затрутся при очередной генерации.',
			],
		]);
	}
	
}
