<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;
use yii\bootstrap\BootstrapAsset;
use yii2lab\helpers\yii\FileHelper;
use yii2lab\helpers\yii\Html;
use yii2lab\extension\store\Store;

class Debug {
	
	private static $isLogged = false;
	
	public static function log($val) {
		$url = Yii::$app->request->url;
		if(!empty($url) && strpos($url, '/debug/') !== false) {
			return null;
		}
		$file = Yii::getAlias('@runtime/logs/debug') . DS . date('Y-m-d', TIMESTAMP).'.log';
		if(file_exists($file)) {
			$log = FileHelper::load($file);
		} else {
			$log = '';
		}
		if(is_object($val)) {
			$val = ArrayHelper::toArray($val);
		}
		$store = new Store('php');
		$content = $store->encode($val);
		if(self::$isLogged) {
			$log .= PHP_EOL . PHP_EOL . ' ------ ' . PHP_EOL . PHP_EOL;
		} else {
			$spliter = SPC . str_repeat('=', 30) . SPC;
			$log .= PHP_EOL . PHP_EOL . $spliter . date('H-i-s', TIMESTAMP) . $spliter . PHP_EOL . PHP_EOL;
		}
		$log .= $content;
		FileHelper::save($file, $log);
		self::$isLogged = true;
	}
	
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
		$val = Html::recursiveHtmlEntities($val);
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
		if(!empty(Yii::$app->view)) {
			BootstrapAsset::register(Yii::$app->view);
			Yii::$app->view->registerCss('body { margin: 20px; }');
			Page::beginDraw();
			echo $content;
			Page::endDraw();
		} else {
			echo $content;
		}
		exit;
	}

}
