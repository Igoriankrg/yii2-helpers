<?php

namespace yii2lab\helpers\generator;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\helpers\yii\FileHelper;

class FileGeneratorHelper {
	
	public static function generate($data) {
		$code = self::generateCode($data);
		$fileName = Yii::getAlias($data['dirAlias'].'/'.$data['baseName'].'.php');
		FileHelper::save($fileName, $code);
	}
	
	private static function generateCode($data) {
		$data['code'] = ArrayHelper::getValue($data, 'code');
		$data['code'] = trim($data['code'], PHP_EOL);
		$data['code'] = PHP_EOL . $data['code'];
		$code = self::getClassCodeTemplate();
		$code = str_replace('{code}', $data['code'], $code);
		return $code;
	}

	private static function getClassCodeTemplate() {
		$code = <<<'CODE'
<?php
{code}
CODE;
		return $code;
	}
	
}
