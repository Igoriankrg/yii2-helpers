<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;
use yii\bootstrap\BootstrapAsset;
use yii2lab\store\Store;

class Debug {
	
	public static function prr($val, $exit = false, $forceToArray = false) {
		if(!empty($forceToArray)) {
			$val = ArrayHelper::toArray($val);
		}
		if(class_exists('Yii')) {
			self::varDump($val, $exit);
		} else {
			$content = '<pre style="font-size: 8pt;">' . print_r($val, 1) . '</pre>';
			echo $content;
			if($exit) {
				exit;
			}
		}
	}
	
	public static function varDump($val, $exit = false) {
		if(APP == API) {
			if(is_object(Yii::$app)) {
				$response = Yii::$app->getResponse();
				$response->clearOutputBuffers();
				$response->setStatusCode(200);
				//$response->format = \yii\web\Response::FORMAT_JSON;
				$response->content = json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
				$response->send();
				Yii::$app->end();
			} else {
				echo json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		$val = self::recursiveHtmlentities($val);
		$store = new Store('php');
		$content = $store->encode($val);
		if(APP != CONSOLE && APP != API) {
			$content = '<pre style="font-size: 8pt;">' . $content . '</pre>';
		}
		if($exit) {
			self::showContent($content);
			exit;
		}
		echo $content;
	}

	private static function showContent($content)
	{
		if(APP == CONSOLE) {
			echo $content;
			exit;
		}
		BootstrapAsset::register(Yii::$app->view);
		Yii::$app->view->registerCss('body { margin: 20px; }');
		Page::beginDraw();
		echo $content;
		Page::endDraw();
		exit;
	}
	
	private static function recursiveHtmlentities($val) {
		if(is_object($val)) {
			$val = (array) $val;
		}
		if(is_array($val)) {
			$closure = function($v) {
				if( ! is_array($v) && ! is_object($v)) {
					$v = htmlentities($v);
				}
				return $v;
			};
			$val = ArrayHelper::recursiveIterator($val, $closure);
		} else {
			$val = htmlentities($val);
		}
		return $val;
	}

}
