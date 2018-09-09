<?php
namespace tests\unit\helpers;

use yii2lab\test\Test\Unit;
use yii2lab\extension\yii\helpers\FileHelper;

class FileHelperTest extends Unit
{
	
	public function testFileExt()
	{
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists.file';
		$ext = FileHelper::fileExt($fileName);
		$this->tester->assertEquals($ext, 'file');
		
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists';
		$ext = FileHelper::fileExt($fileName);
		$this->tester->assertEquals($ext, null);
	}
	
	public function testFileRemoveExt()
	{
		$fileNameWithExt = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists.file';
		$fileNameWithOutExt = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists';
		$ext = FileHelper::fileRemoveExt($fileNameWithExt);
		$this->tester->assertEquals($ext, $fileNameWithOutExt);
		
		$ext = FileHelper::fileRemoveExt($fileNameWithOutExt);
		$this->tester->assertEquals($ext, $fileNameWithOutExt);
		
		$ext = FileHelper::fileRemoveExt('index.php');
		$this->tester->assertEquals($ext, 'index');
	}
	
	public function testLoadData()
	{
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/data/main.php';
		$result = FileHelper::loadData($fileName, 'aliases.@npm');
		$this->tester->assertEquals($result, '@vendor/npm-asset');
		
		$result = FileHelper::loadData($fileName);
		$this->tester->assertEquals($result, [
			'bootstrap' => ['log', 'language', 'queue'],
			'timeZone' => 'UTC',
			'aliases' => [
				'@bower' => '@vendor/bower-asset',
				'@npm' => '@vendor/npm-asset',
			],
		]);
	}
	
	public function testGetPath()
	{
		$expected = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$fileName = 'common/data\user.php';
		$result = FileHelper::getPath($fileName);
		$this->tester->assertEquals($result, $expected);
		
		$fileName = '@common/data\user.php';
		$result = FileHelper::getPath($fileName);
		$this->tester->assertEquals($result, $expected);
		
		$fileName = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		$result = FileHelper::getPath($fileName);
		$this->tester->assertEquals($result, $expected);
	}
	
	public function testDirLevelUp()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::dirLevelUp($path, 0);
		$this->tester->assertEquals($result, $path);
		
		$result = FileHelper::dirLevelUp($path,1);
		$this->tester->assertEquals($result, ROOT_DIR . DS . 'common' . DS . 'data');
		
		$result = FileHelper::dirLevelUp($path,2);
		$this->tester->assertEquals($result, ROOT_DIR . DS . 'common');
		
		$result = FileHelper::dirLevelUp($path,3);
		$this->tester->assertEquals($result, ROOT_DIR);
	}
	
	public function testNormalizeAlias()
	{
		$result = FileHelper::normalizeAlias('@common/data\rbac');
		$this->tester->assertEquals($result, '@common/data/rbac');
		
		$result = FileHelper::normalizeAlias('common/data\rbac');
		$this->tester->assertEquals($result, '@common/data/rbac');
	}
	
	public function testPathToAbsolute()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::pathToAbsolute($path);
		$this->tester->assertEquals($result, $path);
		
		$result = FileHelper::pathToAbsolute('common' . DS . 'data' . DS . 'user.php');
		$this->tester->assertEquals($result, $path);
	}
	
	public function testIsAlias()
	{
		$result = FileHelper::isAlias(ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php');
		$this->tester->assertFalse($result);
		
		$result = FileHelper::isAlias('common' . DS . 'data' . DS . 'user.php');
		$this->tester->assertFalse($result);
		
		$result = FileHelper::isAlias('@common/data\rbac');
		$this->tester->assertTrue($result);
	}
	
	public function testGetAlias()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::getAlias($path);
		$this->tester->assertEquals($result, $path);
		
		$result = FileHelper::getAlias('common' . DS . 'data' . DS . 'user.php');
		$this->tester->assertEquals($result, $path);
		
		$result = FileHelper::getAlias('@common/data\user.php');
		$this->tester->assertEquals($result, $path);
	}
	
	public function testFindInFileByExp()
	{
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/data/main.php';
		$expected = [
			'bower-asset',
			'npm-asset',
		];
		
		$result = FileHelper::findInFileByExp($fileName, '[a-z]+-asset');
		$this->tester->assertEquals($result, [[$expected]]);
	}
	
	public function testRemove()
	{
		$dirName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/_data/new';
		$fileName = $dirName . '.txt';
		if(!is_dir($dirName)) {
			mkdir($dirName);
		}
		file_put_contents($fileName, '');
		
		$result = FileHelper::remove($fileName);
		$this->tester->assertTrue($result);
		$this->tester->assertFalse(file_exists($fileName));
		
		$result = FileHelper::remove($dirName);
		$this->tester->assertTrue($result);
		$this->tester->assertFalse(is_dir($dirName));
		
		// not existed
		
		$result = FileHelper::remove($fileName . '_fake');
		$this->tester->assertFalse($result);
		
		$result = FileHelper::remove($dirName . '_fake');
		$this->tester->assertFalse($result);
	}
	
	public function testIsAbsolute()
	{
		$fileName = 'yii2lab/yii2-helpers/tests/store/exists.file';
		
		$result = FileHelper::isAbsolute($fileName);
		$this->tester->assertFalse($result);
		
		$result = FileHelper::isAbsolute(VENDOR_DIR . DS . $fileName);
		$this->tester->assertTrue($result);
	}
	
	public function testRootPath()
	{
		$result = FileHelper::rootPath();
		expect($result)->notEmpty();
	}
	
	public function testTrimRootPath()
	{
		$fileName = 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		
		$result = FileHelper::trimRootPath(ROOT_DIR . DS . $fileName);
		$this->tester->assertEquals($result, $fileName);
		
		$result = FileHelper::trimRootPath($fileName);
		$this->tester->assertEquals($result, $fileName);
	}
	
	public function testUp()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::up($path, 0);
		$this->tester->assertEquals($result, $path);
		
		$result = FileHelper::up($path,1);
		$this->tester->assertEquals($result, ROOT_DIR . DS . 'common' . DS . 'data');
		
		$result = FileHelper::up($path,2);
		$this->tester->assertEquals($result, ROOT_DIR . DS . 'common');
		
		$result = FileHelper::up($path,3);
		$this->tester->assertEquals($result, ROOT_DIR);
	}
	
	public function testIsEqualContent()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		$fileName2 = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists2.file';
		
		$result = FileHelper::isEqualContent($fileName, $fileName);
		$this->tester->assertTrue($result);
		
		$result = FileHelper::isEqualContent($fileName, $fileName2);
		$this->tester->assertFalse($result);
	}
	
	public function testCopy()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		$fileName2 = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists_copy.file';
		
		FileHelper::remove($fileName2);
		FileHelper::copy($fileName, $fileName2);
		$result = FileHelper::isEqualContent($fileName, $fileName2);
		$this->tester->assertTrue($result);
	}
	
	public function testSave()
	{
		$fileName2 = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists_saved.file';
		
		FileHelper::remove($fileName2);
		FileHelper::save($fileName2, 'hgfd');
		$result = file_get_contents($fileName2);
		$this->tester->assertEquals($result, 'hgfd');
	}
	
	public function testLoad()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		
		$result = FileHelper::load($fileName);
		$expected = file_get_contents($fileName);
		$this->tester->assertEquals($result, $expected);
	}
	
	public function testHas()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		
		$result = FileHelper::has($fileName);
		$this->tester->assertTrue($result);
		
		$result = FileHelper::has($fileName . '_fake');
		$this->tester->assertFalse($result);
	}
	
	public function testNormalizePathList()
	{
		$pathList = [
			'\\ggg////////rrr',
		];
		
		$result = FileHelper::normalizePathList($pathList);
		expect($result, [
			'\ggg\\rrr',
		]);
	}
	
	public function testScanDir()
	{
		$result = FileHelper::scanDir(ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/_application/common/config');
		$this->tester->assertEquals($result, [
			'bootstrap.php',
			'domains.php',
			'env-local.php',
			'env.php',
			'main.php',
		]);
	}
	
	
	public function testDirFromTime()
	{
		$result = FileHelper::dirFromTime(1, 1521444057);
		$this->tester->assertEquals($result, '2018');
		
		$result = FileHelper::dirFromTime(2, 1521444057);
		$this->tester->assertEquals($result, '2018' . DS . '03');
		
		$result = FileHelper::dirFromTime(3, 1521444057);
		$this->tester->assertEquals($result, '2018' . DS . '03' . DS . '19');
		
		$result = FileHelper::dirFromTime(4, 1521444057);
		$this->tester->assertEquals($result, '2018' . DS . '03' . DS . '19' . DS . '07');
		
		$result = FileHelper::dirFromTime(5, 1521444057);
		$this->tester->assertEquals($result, '2018' . DS . '03' . DS . '19' . DS . '07' . DS . '20');
		
		$result = FileHelper::dirFromTime(6, 1521444057);
		$this->tester->assertEquals($result, '2018' . DS . '03' . DS . '19' . DS . '07' . DS . '20' . DS . '57');
	}
	
	public function testFileFromTime()
	{
		$result = FileHelper::fileFromTime(1, 1521444057);
		$this->tester->assertEquals($result, '2018');
		
		$result = FileHelper::fileFromTime(2, 1521444057);
		$this->tester->assertEquals($result, '2018.03');
		
		$result = FileHelper::fileFromTime(3, 1521444057);
		$this->tester->assertEquals($result, '2018.03.19');
		
		$result = FileHelper::fileFromTime(4, 1521444057);
		$this->tester->assertEquals($result, '2018.03.19_07');
		
		$result = FileHelper::fileFromTime(5, 1521444057);
		$this->tester->assertEquals($result, '2018.03.19_07.20');
		
		$result = FileHelper::fileFromTime(6, 1521444057);
		$this->tester->assertEquals($result, '2018.03.19_07.20.57');
	}
	
	public function testFindFilesWithPath()
	{
		$result = FileHelper::findFilesWithPath(ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/_application');
		$this->tester->assertArraySubset($result, [
			'common' . DS . 'config' . DS . 'bootstrap.php',
			'common' . DS . 'config' . DS . 'domains.php',
			'common' . DS . 'config' . DS . 'env-local.php',
			'common' . DS . 'config' . DS . 'env.php',
			'common' . DS . 'config' . DS . 'main.php',
		]);
	}
	
}
