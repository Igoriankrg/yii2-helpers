<?php

namespace yii2lab\helpers\generator;

use yii\helpers\Inflector;
use yii2lab\helpers\yii\ArrayHelper;

class ClassGeneratorHelper {
	
	public static function toConstName($name) {
		$constName = $name;
		$constName = Inflector::camel2id($constName);
		$constName = str_replace(['.','-'], '_', $constName);
		$constName = str_replace('__', '_', $constName);
		$constName = strtoupper($constName);
		return $constName;
	}
	
	public static function generate($data) {
		$data['dirAlias'] = dirname($data['className']);
		$data['baseName'] = basename($data['className']);
		$code = self::generateCode($data);
		$data['code'] = $code;
		FileGeneratorHelper::generate($data);
	}
	
	private static function generateCode($data) {
		$namespace = trim($data['dirAlias'], '@/\\');
		$namespace = str_replace('/', '\\', $namespace);
		
		$data['code'] = ArrayHelper::getValue($data, 'code');
		$data['const'] = ArrayHelper::getValue($data, 'const');
		$data['use'] = ArrayHelper::getValue($data, 'use');
		$data['doc'] = ArrayHelper::getValue($data, 'doc');
		$data['baseName'] = ArrayHelper::getValue($data, 'baseName');
		$data['afterClassName'] = ArrayHelper::getValue($data, 'afterClassName');
		
		$const = self::genConstCode($data['const']);
		if(!empty($const)) {
			$dataCode = !empty($data['code']) ? PHP_EOL . $data['code'] : '';
			$data['code'] = $const . $dataCode;
		}
		
		$data['code'] = trim($data['code'], PHP_EOL);
		$data['code'] = PHP_EOL . $data['code'] . PHP_EOL;
		
		$code = self::getClassCodeTemplate();
		$code = str_replace('{use}', self::generateUse($data['use']), $code);
		$code = str_replace('{doc}', self::generateDoc($data['doc']), $code);
		$code = str_replace('{code}', $data['code'], $code);
		$code = str_replace('{className}', $data['baseName'], $code);
		$code = str_replace('{afterClassName}', !empty($data['afterClassName']) ? ' ' . $data['afterClassName'] : '', $code);
		$code = str_replace('{namespace}', $namespace, $code);
		return $code;
	}
	
	private static function genConstCode($list) {
		if(empty($list)) {
			return '';
		}
		$code = '';
		foreach($list as $data) {
			$code .= PHP_EOL;
			if(!empty($data['description'])) {
				$code .= TAB . "// " . $data['description'] . PHP_EOL;
			}
			$name = self::toConstName($data['name']);
			$code .= TAB . "const {$name} = '{$data['value']}';" . PHP_EOL;
		}
		return $code;
	}
	
	private static function generateUse($list) {
		if(empty($list)) {
			return '';
		}
		$list = ArrayHelper::toArray($list);
		$code = '';
		foreach($list as $item) {
			$code .= PHP_EOL . 'use ' . $item . ';';
		}
		$code .= PHP_EOL;
		return $code;
	}
	
	private static function generateDoc($list) {
		if(empty($list)) {
			return '';
		}
		$list = ArrayHelper::toArray($list);
		$code = '';
		foreach($list as $item) {
			$code .= PHP_EOL . ' * ' . $item . '';
		}
		$code .= PHP_EOL . ' * ';
		return $code;
	}
	
	private static function getClassCodeTemplate() {
		$code = <<<'CODE'
namespace {namespace};
{use}
/**
 * Class {className}
 * {doc}
 * @package {namespace}
 */
class {className}{afterClassName} {
{code}
}
CODE;
		return $code;
	}
	
}
