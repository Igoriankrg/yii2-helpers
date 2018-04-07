<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\Domain;
use yii2lab\domain\helpers\ConfigHelper;
use yii2lab\helpers\yii\ArrayHelper;

class DomainHelper
{
	
	public static function define($domainId, $definition) {
		$definition = ConfigHelper::normalizeItemConfig($domainId, $definition);
		if(!Yii::$domain->has($domainId)) {
			Yii::$domain->set($domainId, $definition);
		}
	}
	
	public static function getClassConfig(string $domainId, $className, array $classDefinition = null) {
		$definition = self::getConfigFromDomainClass($className);
		$definition = ConfigHelper::normalizeItemConfig($domainId, $definition);
		if(!empty($classDefinition)) {
			$classDefinition =  ConfigHelper::normalizeItemConfig($domainId, $classDefinition);
			$definition = ArrayHelper::merge($definition, $classDefinition);
		}
		$definition['class'] = $className;
		return $definition;
	}
	
	private static function getConfigFromDomainClass($className) {
		$definition = ClassHelper::normalizeComponentConfig($className);
		/** @var Domain $domain */
		$domain = Yii::createObject($definition);
		$config = $domain->config();
		return $config;
	}
	
	public static function isEntity($data) {
		return is_object($data) && $data instanceof BaseEntity;
	}
	
	public static function isCollection($data) {
		return is_array($data);
	}
	
	public static function has($name) {
		if(!Yii::$app->has($name)) {
			return false;
		}
		$domain = !Yii::$app->get($name);
		if(!$domain instanceof Domain) {
			return false;
		}
		return true;
	}
	
	public static function messagesAlias($bundleName) {
		if(!Yii::$app->has($bundleName)) {
			return false;
		}
		$domain = ArrayHelper::getValue(Yii::$app, $bundleName);
		if(empty($domain) || empty($domain->path)) {
			return null;
		}
		return Helper::getBundlePath($domain->path . SL . 'messages');
	}
	
}
