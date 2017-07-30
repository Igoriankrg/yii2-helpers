<?php

namespace yii2lab\helpers;

use Yii;
use yii\helpers\Url;

class MenuHelper
{

	public static function gen($items) {
		$result = [];
		foreach($items as $item) {
			$menu = self::genItem($item);
			if(!empty($menu)) {
				$result[] = $menu;
			}
		}
		return $result;
	}

	private static function genItem($menu)
	{
		if(!self::isHasModule($menu) || !self::isAllow($menu)) {
			return false;
		}
		$menu = self::runClass($menu);
		if(!empty($menu['items'])) {
			$menu['items'] = MenuHelper::genList($menu['items']);
			$menu['url'] = '#';
		}
		if(self::isHeader($menu)) {
			$menu['options'] = ['class' => 'header'];
			//$menu['icon'] = 'star';
		}
		if(empty($menu['icon'])) {
			//$menu['icon'] = 'square-o';
		}
		return self::preMenuItem($menu);
	}
	
	private static function isHeader($menu) {
		return $menu['isHeader'];
	}
	
	private static function isActive($menu) {
		if(empty($menu['url'])) {
			return null;
		}
		$currentUrl = Url::to();
		$currentUrl = trim($currentUrl, SL);
		return strpos($currentUrl, $menu['url']) !== false;
	}
	
	private static function isJs($menu) {
		return strpos($menu['url'], 'javascript:') !== false;
	}
	
	private static function isMenu($menu) {
		return $menu['url'] == '#';
	}
	
	private static function runClass($menu) {
		if(empty($menu['class'])) {
			return $menu;
		}
		return call_user_func([$menu['class'], 'getMenu']);
	}
	
	private static function genIcon($menu) {
		if(empty($menu['icon'])) {
			return null;
		}
		return '<i class="fa fa-' . $menu['icon'] . '"></i>';
	}
	
	private static function isHasModule($menu) {
		if(empty($menu['module'])) {
			return true;
		}
		$key = 'modules.' . $menu['module'];
		return config($key);
	}
	
	private static function isUrl($menu)
	{
		return !empty($menu['url']) && !self::isJs($menu) && !self::isMenu($menu);
	}
	
	private static function isAllow($menu) {
		$isAccess = false;
		if(empty($menu['access'])) {
			return true;
		}
		foreach($menu['access'] as $accessItem) {
			if(Yii::$app->user->can($accessItem)) {
				$isAccess = true;
				break;
			}
		}
		return $isAccess;
	}
	
	private static function translateLabel($label)
	{
		if(is_array($label)) {
			$label = call_user_func_array('t', $label);
		}
		return $label;
	}
	
	private static function preMenuItem($menu)
	{
		$menu['label'] = self::translateLabel($menu['label']);
		if(self::isUrl($menu)) {
			$menu['active'] = self::isActive($menu);
			$menu['url'] = SL . $menu['url'];
		} elseif(!empty($menu['js'])) {
			$menu['url'] = 'javascript: ' . $menu['js'];
		}
		$menu['icon'] = self::genIcon($menu);
		return $menu;
	}
	
	private static function genList($list)
	{
		$result = [];
		foreach($list as $item) {
			$result[] = MenuHelper::genItem($item);
		}
		return $result;
	}
	
}
