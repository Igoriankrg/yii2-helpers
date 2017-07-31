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
		$menu['label'] = self::translateLabel($menu['label']);
		if(self::isHeader($menu)) {
			$menu['options'] = ['class' => 'header'];
			return $menu;
		}
		$menu = self::runClass($menu);
		$menu = self::genChilds($menu);
		$menu['active'] = self::isActive($menu);
		$menu['url'] = self::genUrl($menu);
		$menu['icon'] = self::genIcon($menu);
		return $menu;
	}
	
	private static function runClass($menu) {
		if(empty($menu['class'])) {
			return $menu;
		}
		return call_user_func([$menu['class'], 'getMenu']);
	}
	
	private static function genChilds($menu) {
		if(!empty($menu['items'])) {
			$menu['items'] = MenuHelper::gen($menu['items']);
			$menu['url'] = '#';
		}
		return $menu;
	}
	
	private static function genIcon($menu) {
		if(empty($menu['icon'])) {
			return null;
		}
		return '<i class="fa fa-' . $menu['icon'] . '"></i>';
	}
	
	private static function translateLabel($label)
	{
		if(is_array($label)) {
			$label = call_user_func_array('t', $label);
		}
		return $label;
	}
	
	private static function genUrl($menu)
	{
		if(!empty($menu['js'])) {
			return 'javascript: ' . $menu['js'];
		}
		/* if(isset($menu['url']) && $menu['url'] == '#') {
			return $menu['url'];
		} */
		return SL . $menu['url'];
	}
	
	private static function isActiveChild($menu) {
		foreach($menu['items'] as $item) {
			if(!empty($item['active'])) {
				return true;
			}
		}
		return false;
	}
	
	private static function isHeader($menu) {
		return !empty($menu['isHeader']);
	}
	
	private static function isActive($menu) {
		if(isset($menu['active'])) {
			return $menu['active'];
		}
		if(!empty($menu['items'])) {
			return self::isActiveChild($menu);
		}
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
	
	private static function isHasModule($menu) {
		if(empty($menu['module'])) {
			return true;
		}
		$key = 'modules.' . $menu['module'];
		return config($key);
	}
	
	/* private static function isUrl($menu)
	{
		return !empty($menu['url']) && !self::isJs($menu) && !self::isMenu($menu);
	} */
	
	private static function isAllow($menu) {
		if(empty($menu['access'])) {
			return true;
		}
		$user = Yii::$app->user;
		foreach($menu['access'] as $rule) {
			if($rule === '?') {
				if($user->getIsGuest()) {
                    return true;
                }
			} elseif($rule === '@') {
				if(!$user->getIsGuest()) {
                    return true;
                }
			} elseif($user->can($rule)) {
				return true;
			}
		}
		return false;
	}
	
}
