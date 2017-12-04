<?php

namespace yii2lab\helpers;

use Yii;
use yii\helpers\Inflector;
use yii2lab\helpers\yii\FileHelper;

class EnumGeneratorHelper {
	
	public static function toConstName($name) {
		$constName = $name;
		$constName = Inflector::camel2id($constName);
		$constName = str_replace(['.','-'], '_', $constName);
		$constName = str_replace('__', '_', $constName);
		$constName = strtoupper($constName);
		return $constName;
	}
	
	public static function generateClass($className, $constList) {
		$dirAlias = dirname($className);
		$className = basename($className);
		$code = EnumGeneratorHelper::generateClassCode($dirAlias, $className, $constList);
		$fileName = Yii::getAlias($dirAlias.'/'.$className.'.php');
		FileHelper::save($fileName, $code);
	}
	
	private static function generateClassCode($dirAlias, $className, $constList) {
		$constCode = self::genConstCode($constList);
		$namespace = trim($dirAlias, '@/\\');
		$namespace = str_replace('/', '\\', $namespace);
		$code = self::getClassCodeTemplate();
		$code = str_replace('{constCode}', $constCode, $code);
		$code = str_replace('{className}', $className, $code);
		$code = str_replace('{namespace}', $namespace, $code);
		return $code;
	}
	
	private static function genConstCode($constList) {
		$constCode = '';
		foreach($constList as $value) {
			$constCode .= PHP_EOL;
			if(!empty($value['description'])) {
				$constCode .= TAB . "// " . $value['description'] . PHP_EOL;
			}
			$name = self::toConstName($value['name']);
			$constCode .= TAB . "const {$name} = '{$value['value']}';" . PHP_EOL;
		}
		return $constCode;
	}
	
	private static function getClassCodeTemplate() {
		$code = <<<'CODE'
<?php

namespace {namespace};

use yii2lab\misc\enums\BaseEnum;

/**
 * Class {className}
 *
 * Этот класс был сгенерирован автоматически.
 * Не вносите в данный файл изменения, они затрутся при очередной генерации
 *
 * @package {namespace}
 */
class {className} extends BaseEnum {
{constCode}
}
CODE;
		return $code;
	}
	
}
