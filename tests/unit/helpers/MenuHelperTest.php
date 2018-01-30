<?php
namespace tests\unit\helpers;

use Codeception\Test\Unit;
use yii2lab\helpers\MenuHelper;
use yii2lab\test\helpers\DataHelper;

class MenuHelperTest extends Unit
{
	
	const PACKAGE = 'yii2lab/yii2-helpers';
	
	public function testGenerateMenu()
	{
		$menu = DataHelper::load(self::PACKAGE, 'store/source/menu.php');
		$resultMenu = MenuHelper::gen($menu);
		$expect = DataHelper::load(self::PACKAGE, 'store/expect/generatedMenu.php', $resultMenu);
		expect($expect)->equals($resultMenu);
	}
	
	public function testGenerateMenu2()
	{
		$menu = DataHelper::load(self::PACKAGE, 'store/source/menu.php');
		$resultMenu = MenuHelper::renderMenu([
			[
				'label' => 'Rbac permission',
				'url' => '/rbac/permission',
				'active' => false,
				'icon' => null,
			],
			[
				'label' => 'Rbac role',
				'url' => '/rbac/role',
				'active' => false,
				'icon' => null,
			],
			[
				'label' => 'Rbac rule',
				'url' => '/rbac/rule',
				'active' => false,
				'icon' => null,
			],
			[
				'label' => 'Rbac assignment',
				'url' => '/rbac/assignment',
				'active' => false,
				'icon' => null,
			],
		]);
		$expect = DataHelper::load(self::PACKAGE, 'store/expect/renderedMenu.php', $resultMenu);
		expect($expect)->equals($resultMenu);
	}
	
}
