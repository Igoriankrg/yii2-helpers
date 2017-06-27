<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helpers\yii\FileHelper;

class MenuHelper
{

	public static function buildNavbarMenu($items) {
		$result = [];
		foreach($items as $module) {
			if(config('modules.' . $module['name'])) {
				if(!empty($module['access'])) {
					$isAccess = false;
					foreach($module['access'] as $accessItem) {
						if(Yii::$app->user->can($accessItem)) {
							$isAccess = true;
							break;
						}
					}
					if(!$isAccess) {
						continue;
					}
				}
				if(!empty($module['class'])) {
					$navClass = $module['class'];
					$result[] = $navClass::getMenu();
				} else {
					$result[] = [
						'label' => $module['label'],
						'url' => !empty($module['url']) ? $module['url'] : ['/' . $module['name']],
					];
				}
			}
		}
		return $result;
	}

	public static function genMenuItem($unit, $module)
	{
		$currentUrl = Yii::$app->controller->module->id . SL . Yii::$app->controller->id;
		$ctrlName = strtolower($unit);
		$url = $module . SL . $ctrlName;
		return[
			'label' => $unit, 
			'url' => [SL . $url],
			'icon' => '<i class="fa fa-genderless"></i>',
			'active' => $url == $currentUrl, 
		];
	}
	
	private static function getControllersDir($module) {
		$moduleClass = config("modules.{$module}.class");
		if(empty($moduleClass)) {
			return;
		}
		$moduleClass = str_replace(BSL, SL, $moduleClass);
		$modulePath = Yii::getAlias('@' . $moduleClass);
		return dirname($modulePath) . DS . 'controllers';
	}
	
	private static function getUnitsFromDir($dir, $mask, $to = '$1') {
		if( ! is_dir($dir)) {
			return [];
		}
		$fileList = FileHelper::scandir($dir);
		$maskLen = strlen($mask);
		foreach($fileList as $file) {
			$unitList[] = preg_replace("/{$mask}/", $to, $file);
			//$unitList[] = substr($file, 0, 0 - $maskLen);
		}
		return $unitList;
	}
	
	public static function genMenu($menu)
	{
		$result['label'] = !empty($menu['label']) ? $menu['label'] : mb_ucfirst($menu['name']);
		$result['icon'] = !empty($menu['icon']) ? $menu['icon'] : '<i class="fa fa-square-o"></i>';
		$dir = self::getControllersDir($menu['name']);
		if(empty($dir)) {
			return;
		}
		$menu['mask'] = '(.+)Controller\.php';
		$unitList = self::getUnitsFromDir($dir, $menu['mask']);
		if(empty($unitList)) {
			return false;
		}
		if(count($unitList) > 1) {
			foreach($unitList as $unit) {
				$partItems[] = self::genMenuItem($unit, $menu['name']);
			}
			$result['items'] = $partItems;
			$result['url'] = ['#'];
		} else {
			$result['url'] = [SL . $menu['name']];
			$result['active'] = $menu['name'] == Yii::$app->controller->module->id;
		}
		return $result;
	}

	protected static function getRoute($item)
	{
		if(empty($item['url'])) {
			return false;
		}
		$route = ! is_array($item['url']) ? $item['url'] : $item['url'][0];
		if(empty($route) || $route == '#' || strpos($route, 'javascript:') === 0) {
			return false;
		}
		return $route;
	}

	protected static function normalizeMenuItem($item)
	{
		$route = self::getRoute($item);
		if($route) {
			$item['active'] = ActiveHelper::check($route);
		}
		return $item;
	}

	static function normalizeMenu($menu, $callback = null)
	{
		if(isset($menu['url'])) {
			$menu = self::normalizeMenuItem($menu);
		}
		if(empty($menu['items']) || ! is_array($menu['items'])) {
			return $menu;
		}
		foreach ($menu['items'] as $item) {
			$item = self::normalizeMenu($item, $callback);
		}
		return $menu;
	}

	static function normalizeMenuList($menu)
	{
		$result = [];
		foreach ($menu as $i => $item) {
			if(isset($item['url'])) {
				$item = self::normalizeMenu($item);

			}
			if(!empty($item)) {
				$result[] = $item;
			}
		}
		return $result;
	}
}
