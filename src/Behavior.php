<?php

namespace yii2lab\helpers;

use yii\filters\AccessControl;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\v1\filters\auth\HttpTokenAuth;

class Behavior {
	
	static function modifyActions() {
		return ['create', 'update', 'delete'];
	}
	
	static function apiAuth($only = null) {
		$config = [
			'class' => HttpTokenAuth::className(),
		];
		if(!empty($only)) {
			$config['only'] = ArrayHelper::toArray($only);
		}
		return $config;
	}
	
	static function verb($actions) {
		foreach($actions as $actionName => &$actionMethods) {
			$actionMethods = ArrayHelper::toArray($actionMethods);
		}
		$config = [
			'class' => VerbFilter::className(),
			'actions' => $actions,
		];
		return $config;
	}
	
	static function access($roles, $only = null, $allow = true) {
		$roles = is_array($roles) ? $roles : [$roles];
		$config = [
			'class' => AccessControl::className(),
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
			$cors = self::generate();
		}
		return $cors;
	}
	
	private static function generate() {
		return [
			'class' => Cors::className(),
			'cors' => [
				'Origin' => self::generateOriginFromEnvUrls(),
				'Access-Control-Request-Method' => ['get', 'post', 'put', 'delete', 'options'],
				'Access-Control-Request-Headers' => [
					//'X-Wsse',
					'content-type',
					'x-requested-with',
					'authorization',
					'registration-token',
				],
				//'Access-Control-Allow-Credentials' => true,
				//'Access-Control-Max-Age' => 3600, // Allow OPTIONS caching
				'Access-Control-Expose-Headers' => [
					'link',
					'access-token',
					'authorization',
					'x-pagination-total-count',
					'x-pagination-page-count',
					'x-pagination-current-page',
					'x-pagination-per-page',
				],
			],
		];
	}
	
	private static function generateOriginFromEnvUrls() {
		$origin = [];
		$urls = env('url');
		foreach($urls as $url) {
			$origin[] = trim($url, SL);
		}
		return $origin;
	}
	
}
