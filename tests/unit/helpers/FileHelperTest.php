<?php
namespace tests\unit\helpers;

use Codeception\Test\Unit;
use yii2lab\helpers\yii\FileHelper;

class FileHelperTest extends Unit
{
	
	public function testFileExt()
	{
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists.file';
		$ext = FileHelper::fileExt($fileName);
		expect($ext)->equals('file');
		
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists';
		$ext = FileHelper::fileExt($fileName);
		expect($ext)->equals(null);
	}
	
	public function testFileRemoveExt()
	{
		$fileNameWithExt = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists.file';
		$fileNameWithOutExt = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/exists';
		$ext = FileHelper::fileRemoveExt($fileNameWithExt);
		expect($ext)->equals($fileNameWithOutExt);
		
		$ext = FileHelper::fileRemoveExt($fileNameWithOutExt);
		expect($ext)->equals($fileNameWithOutExt);
	}
	
	public function testLoadData()
	{
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/data/main.php';
		$result = FileHelper::loadData($fileName, 'aliases.@npm');
		expect($result)->equals('@vendor/npm-asset');
		
		$result = FileHelper::loadData($fileName);
		expect($result)->equals([
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
		expect($result)->equals($expected);
		
		$fileName = '@common/data\user.php';
		$result = FileHelper::getPath($fileName);
		expect($result)->equals($expected);
		
		$fileName = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		$result = FileHelper::getPath($fileName);
		expect($result)->equals($expected);
	}
	
	public function testDirLevelUp()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::dirLevelUp($path, 0);
		expect($result)->equals($path);
		
		$result = FileHelper::dirLevelUp($path,1);
		expect($result)->equals(ROOT_DIR . DS . 'common' . DS . 'data');
		
		$result = FileHelper::dirLevelUp($path,2);
		expect($result)->equals(ROOT_DIR . DS . 'common');
		
		$result = FileHelper::dirLevelUp($path,3);
		expect($result)->equals(ROOT_DIR);
	}
	
	public function testNormalizeAlias()
	{
		$result = FileHelper::normalizeAlias('@common/data\rbac');
		expect($result)->equals('@common/data/rbac');
		
		$result = FileHelper::normalizeAlias('common/data\rbac');
		expect($result)->equals('@common/data/rbac');
	}
	
	public function testPathToAbsolute()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::pathToAbsolute($path);
		expect($result)->equals($path);
		
		$result = FileHelper::pathToAbsolute('common' . DS . 'data' . DS . 'user.php');
		expect($result)->equals($path);
	}
	
	public function testIsAlias()
	{
		$result = FileHelper::isAlias(ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php');
		expect($result)->false();
		
		$result = FileHelper::isAlias('common' . DS . 'data' . DS . 'user.php');
		expect($result)->false();
		
		$result = FileHelper::isAlias('@common/data\rbac');
		expect($result)->true();
	}
	
	public function testGetAlias()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::getAlias($path);
		expect($result)->equals($path);
		
		$result = FileHelper::getAlias('common' . DS . 'data' . DS . 'user.php');
		expect($result)->equals($path);
		
		$result = FileHelper::getAlias('@common/data\user.php');
		expect($result)->equals($path);
	}
	
	public function testFindInFileByExp()
	{
		$fileName = VENDOR_DIR . DS . 'yii2lab/yii2-helpers/tests/store/data/main.php';
		$expected = [
			'bower-asset',
			'npm-asset',
		];
		
		$result = FileHelper::findInFileByExp($fileName, '[a-z]+-asset');
		expect($result)->equals([[$expected]]);
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
		expect($result)->true();
		expect(file_exists($fileName))->false();
		
		$result = FileHelper::remove($dirName);
		expect($result)->true();
		expect(is_dir($dirName))->false();
		
		// not existed
		
		$result = FileHelper::remove($fileName . '_fake');
		expect($result)->false();
		
		$result = FileHelper::remove($dirName . '_fake');
		expect($result)->false();
	}
	
	public function testIsAbsolute()
	{
		$fileName = 'yii2lab/yii2-helpers/tests/store/exists.file';
		
		$result = FileHelper::isAbsolute($fileName);
		expect($result)->false();
		
		$result = FileHelper::isAbsolute(VENDOR_DIR . DS . $fileName);
		expect($result)->true();
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
		expect($result)->equals($fileName);
		
		$result = FileHelper::trimRootPath($fileName);
		expect($result)->equals($fileName);
	}
	
	public function testUp()
	{
		$path = ROOT_DIR . DS . 'common' . DS . 'data' . DS . 'user.php';
		
		$result = FileHelper::up($path, 0);
		expect($result)->equals($path);
		
		$result = FileHelper::up($path,1);
		expect($result)->equals(ROOT_DIR . DS . 'common' . DS . 'data');
		
		$result = FileHelper::up($path,2);
		expect($result)->equals(ROOT_DIR . DS . 'common');
		
		$result = FileHelper::up($path,3);
		expect($result)->equals(ROOT_DIR);
	}
	
	public function testIsEqualContent()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		$fileName2 = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists2.file';
		
		$result = FileHelper::isEqualContent($fileName, $fileName);
		expect($result)->true();
		
		$result = FileHelper::isEqualContent($fileName, $fileName2);
		expect($result)->false();
	}
	
	public function testCopy()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		$fileName2 = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists_copy.file';
		
		FileHelper::remove($fileName2);
		FileHelper::copy($fileName, $fileName2);
		$result = FileHelper::isEqualContent($fileName, $fileName2);
		expect($result)->true();
	}
	
	public function testSave()
	{
		$fileName2 = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists_saved.file';
		
		FileHelper::remove($fileName2);
		FileHelper::save($fileName2, 'hgfd');
		$result = file_get_contents($fileName2);
		expect($result)->equals('hgfd');
	}
	
	public function testLoad()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		
		$result = FileHelper::load($fileName);
		$expected = file_get_contents($fileName);
		expect($result)->equals($expected);
	}
	
	public function testHas()
	{
		$fileName = ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/store/exists.file';
		
		$result = FileHelper::has($fileName);
		expect($result)->true();
		
		$result = FileHelper::has($fileName . '_fake');
		expect($result)->false();
	}
	
	
	public function testNormalizePathList()
	{
		$pathList = [
			'\\ggg////////rrr',
			ROOT_DIR . DS . '\\///\\//ggg/rrr',
		];
		
		$result = FileHelper::normalizePathList($pathList);
		expect($result)->equals([
			'\\ggg\\rrr',
			'C:\\OpenServer\\domains\\yii\\demo\\ggg\\rrr',
		]);
	}
	
	public function testScanDir()
	{
		$result = FileHelper::scanDir(ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/_application/common/config');
		expect($result)->equals([
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
		expect($result)->equals('2018');
		
		$result = FileHelper::dirFromTime(2, 1521444057);
		expect($result)->equals('2018\03');
		
		$result = FileHelper::dirFromTime(3, 1521444057);
		expect($result)->equals('2018\03\19');
		
		$result = FileHelper::dirFromTime(4, 1521444057);
		expect($result)->equals('2018\03\19\07');
		
		$result = FileHelper::dirFromTime(5, 1521444057);
		expect($result)->equals('2018\03\19\07\20');
		
		$result = FileHelper::dirFromTime(6, 1521444057);
		expect($result)->equals('2018\03\19\07\20\57');
	}
	
	public function testFileFromTime()
	{
		$result = FileHelper::fileFromTime(1, 1521444057);
		expect($result)->equals('2018');
		
		$result = FileHelper::fileFromTime(2, 1521444057);
		expect($result)->equals('2018.03');
		
		$result = FileHelper::fileFromTime(3, 1521444057);
		expect($result)->equals('2018.03.19');
		
		$result = FileHelper::fileFromTime(4, 1521444057);
		expect($result)->equals('2018.03.19_07');
		
		$result = FileHelper::fileFromTime(5, 1521444057);
		expect($result)->equals('2018.03.19_07.20');
		
		$result = FileHelper::fileFromTime(6, 1521444057);
		expect($result)->equals('2018.03.19_07.20.57');
	}
	
	public function testFindFilesWithPath()
	{
		$result = FileHelper::findFilesWithPath(ROOT_DIR . DS . 'vendor/yii2lab/yii2-helpers/tests/_application');
		expect($result)->equals([
			'common\config\bootstrap.php',
			'common\config\domains.php',
			'common\config\env-local.php',
			'common\config\env.php',
			'common\config\main.php',
		]);
	}
	
}
