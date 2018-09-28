<?php

namespace yii2lab\helpers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\v2\filters\auth\ConsoleAuth;
use yii2module\account\domain\v2\filters\auth\HttpTokenAuth;

class Behavior {
	
	static function modifyActions() {
		return ['create', 'update', 'delete'];
	}
	
	static function auth($only = null) {
		if(APP == CONSOLE) {
			return self::consoleAuth($only);
		}
		if(APP == API) {
			return self::apiAuth($only);
		}
	}
	
	static function consoleAuth($only = null) {
		$config = [
			'class' => ConsoleAuth::class,
		];
		if(!empty($only)) {
			$config['only'] = ArrayHelper::toArray($only);
		}
		return $config;
	}
	
	static function apiAuth($only = null) {
		$config = [
			'class' => HttpTokenAuth::class,
		];
		if(!empty($only)) {
			$config['only'] = ArrayHelper::toArray($only);
		}
        $config['except'] = ['options'];
		return $config;
	}
	
	static function verb($actions) {
		foreach($actions as $actionName => &$actionMethods) {
			$actionMethods = ArrayHelper::toArray($actionMethods);
		}
		$config = [
			'class' => VerbFilter::class,
			'actions' => $actions,
		];
		return $config;
	}
	
	static function access($roles, $only = null, $allow = true) {
		$roles = is_array($roles) ? $roles : [$roles];
		$config = [
			'class' => AccessControl::class,
			'rules' => [
				[
					'allow' => $allow,
					'roles' => $roles,
				],
			],
		];
		if(!empty($only)) {
			$config['only'] = ArrayHelper::toArray($only);
		}
		return $config;
	}
	
	static function cors() {
		// todo: guide
		$cors = param('cors.default', false);
		if(!$cors) {
			$cors = CorsHelper::generate();
		}
		return $cors;
	}
	
}
