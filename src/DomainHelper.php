<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\app\domain\Domain;
use yii2lab\domain\BaseEntity;
use yii2lab\helpers\yii\ArrayHelper;

class DomainHelper
{
	
	public static function isEntity($data) {
		return is_object($data) && $data instanceof BaseEntity;
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
