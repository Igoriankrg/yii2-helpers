<?php
namespace yii2lab\helpers\tests\unit;

use Codeception\Test\Unit;
use yii2lab\helpers\Registry;

class RegistryTest extends Unit
{
	
	public function testGetStartValues()
	{
		expect(Registry::get())->equals([]);
	}
	
	public function testSetValues()
	{
		Registry::set('key1', 'value1');
		Registry::set('key2', 'value2');
		Registry::set('key3', 'value3');
	}
	
	public function testGetValue()
	{
		expect(Registry::get('key1'))->equals('value1');
		expect(Registry::get('key2'))->equals('value2');
		expect(Registry::get('key3'))->equals('value3');
	}

	public function testGetAllValues()
	{
		expect(Registry::get())->equals([
			'key1' => 'value1',
			'key2' => 'value2',
			'key3' => 'value3',
		]);
	}
	
	public function testGetNotExistsValue()
	{
		expect(Registry::get('key4'))->equals(null);
		expect(Registry::get('key4', 'default_value4'))->equals('default_value4');
	}
	
	public function testRemoveValue()
	{
		Registry::remove('key3');
		expect(Registry::get('key3'))->equals(null);
		expect(Registry::get('key3', 'default_value3'))->equals('default_value3');
	}
	
	public function testHasKey()
	{
		expect(Registry::has('key1'))->equals(true);
		expect(Registry::has('key3'))->equals(false);
		expect(Registry::has('key333'))->equals(false);
	}
	
}
