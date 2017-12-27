<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;

class DomainHelper
{
	
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
