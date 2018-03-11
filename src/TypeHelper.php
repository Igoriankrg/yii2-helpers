<?php

namespace yii2lab\helpers;

use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\values\BaseValue;
use yii2lab\domain\values\TimeValue;

class TypeHelper {
	
	private static $instance;
	
	private static function decodeValueObject($value) {
		if($value instanceof TimeValue) {
			$value = $value->getInFormat(TimeValue::FORMAT_API);
		} elseif($value instanceof BaseValue) {
			$value = $value->get();
		}
		return $value;
	}
	
	private static function entityToArray($entity) {
		if(method_exists($entity, 'toArrayRaw')) {
			$item = $entity->toArrayRaw();
		} elseif(method_exists($entity, 'toArray')) {
			$item = $entity->toArray();
		} else {
			$item = ArrayHelper::toArray($entity);
		}
		foreach($item as $fieldName => $value) {
			if($value instanceof BaseValue) {
				$item[ $fieldName ] = self::decodeValueObject($value);
			}
			$pureValue = $entity->{$fieldName};
			if($pureValue instanceof BaseEntity) {
				$item[ $fieldName ] = self::entityToArray($pureValue);
			}
		}
		return $item;
	}
	
	private static function normalizeItemTypes($item, $formatMap) {
		foreach($formatMap as $fieldName => $format) {
			if(is_array($format)) {
				if(isset($item[ $fieldName ])) {
					if(ArrayHelper::isIndexed($item[ $fieldName ])) {
						foreach($item[ $fieldName ] as $kk => $vv) {
							$item[ $fieldName ][ $kk ] = self::serialize($vv, $format);
						}
					} else {
						$item[ $fieldName ] = self::serialize($item[ $fieldName ], $format);
					}
				}
				continue;
			}
			if(!array_key_exists($fieldName, $item)) {
				continue;
			}
			if($format == 'hide') {
				unset($item[ $fieldName ]);
			} elseif($format == 'hideIfNull' && empty($item[ $fieldName ])) {
				unset($item[ $fieldName ]);
			} else {
				$item[ $fieldName ] = self::encode($item[ $fieldName ], $format);
			}
		}
		return $item;
	}
	
	public static function serialize($entity, $formatMap) {
		$item = self::entityToArray($entity);
		if(!empty($formatMap)) {
			$item = self::normalizeItemTypes($item, $formatMap);
		}
		return $item;
	}
	
	public static function encode($value, $typeStr) {
		$arr = explode(':', $typeStr);
		$param = null;
		if(count($arr) > 1) {
			list($type, $param) = $arr;
		} else {
			list($type) = $arr;
		}
		$instance = self::getInstance();
		$method = 'type' . ucfirst($type);
		if(method_exists($instance, $method)) {
			$value = $instance->$method($value, $param);
		} elseif(function_exists($type)) {
			if(isset($param)) {
				$value = $type($value, $param);
			} else {
				$value = $type($value);
			}
		}
		return $value;
	}
	
	private static function getInstance() {
		if(empty(self::$instance)) {
			self::$instance = new static;
		}
		return self::$instance;
	}
	
	private function typeInteger($value, $param) {
		$value = intval($value);
		return $value;
	}
	
	private function typeFloat($value, $param) {
		$value = floatval($value);
		return $value;
	}
	
	private function typeString($value, $param) {
		$value = strval($value);
		return $value;
	}
	
	private function typeBoolean($value, $param) {
		$value = !empty($value);
		return $value;
	}
	
	private function typeNull($value, $param) {
		if(empty($value)) {
			$value = null;
		}
		return $value;
	}
	
}