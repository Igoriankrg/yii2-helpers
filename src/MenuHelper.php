<?php

namespace yii2lab\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\misc\enums\HtmlEnum;

//TODO [nkl90]: протестировать класс!
class MenuHelper
{
	
	public static function renderMenu($items, $glue = HtmlEnum::PIPE) {
		$menuArr = [];
		foreach($items as $item) {
			$html = '';
			if(!empty($item['icon'])) {
				$html .= $item['icon'] . ' ';
			}
			$html .= Html::a($item['label'], $item['url']);
			$menuArr[] = $html;
		}
		return implode($glue, $menuArr);
	}
	
	public static function gen($items) {
		if(is_string($items)) {
			$items = self::runClass(['class' => $items]);
		}
		$result = [];
		foreach($items as $index => $config) {
			if(is_string($config)) {
				$config = ['class' => $config];
			}
			$menu = self::genItem($config);
			if(!empty($menu)) {
				$result[] = $menu;
			}
		}
		return $result;
	}

	private static function genItem($menu)
	{
		if(!empty($menu['class'])) {
			$menu = self::runClass($menu);
		}
		if(self::isHidden($menu)) {
			return false;
		}
		$menu['label'] = self::translateLabel($menu);
		if(self::isHeader($menu)) {
			$menu['options'] = ['class' => 'header'];
			return $menu;
		}
		$menu = self::genChilds($menu);
		$menu['url'] = self::genUrl($menu);
		$menu['active'] = self::isActive($menu);
		$menu['icon'] = self::genIcon($menu);
		return $menu;
	}
	
	private static function runClass($menu) {
		if(empty($menu['class'])) {
			return $menu;
		}
		if(!class_exists($menu['class'])) {
			return null;
		}
		$menuEntity = Yii::createObject($menu);
		if(method_exists($menuEntity, 'toArray')) {
			$result = $menuEntity->toArray();
		} elseif(method_exists($menuEntity, 'getMenu')) {
			// todo: @deprecated
			$result = $menuEntity->getMenu();
		}
		return $result;
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
	
	private static function translateLabel($menu)
	{
		$label = ArrayHelper::getValue($menu, 'label');
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
		if(isset($menu['url'])) {
			$url = is_array($menu['url']) ? $menu['url'][0] : $menu['url'];
		}else{
			$url = '';
		}
		if(!UrlHelper::isAbsolute($url)) {
			$url = SL . $url;
		}
		return $url;
	}
	
	private static function isHidden($menu) {
		
		return
			!self::isHasModule($menu) ||
			!self::isHasDomain($menu) ||
			!self::isAllow($menu) ||
			!empty($menu['hide']) ||
			(isset($menu['visible']) && empty($menu['visible']));
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
		if(APP == CONSOLE) {
			$currentUrl = '';
		} else {
			$currentUrl = Url::to();
		}
		$currentUrl = trim($currentUrl, SL);
		$url = $menu['url'];
		$url = trim($url, '/\\');
		$url = str_replace('\\', '/', $url);
		if(empty($url)) {
			return false;
		}
		return strpos($currentUrl, $url) !== false;
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
	
	private static function isHasDomain($menu) {
		if(empty($menu['domain'])) {
			return true;
		}
		$key = 'components.' . $menu['domain'];
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
		$access = $menu['access'];
		$access = is_array($access) ? $access : [$access];
		$user = Yii::$app->user;
		foreach($access as $rule) {
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
