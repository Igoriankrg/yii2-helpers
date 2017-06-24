<?php

namespace yii2lab\helpers;

use Yii;
use yii2lab\helper\yii\Html;

class Page {
	
	static function beginDraw($attrs = []) {
		Yii::$app->view->beginPage() ?>
		<!DOCTYPE html>
		<html lang="<?= Yii::$app->language ?>">
		<head>
			<meta charset="<?= Yii::$app->charset ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?= Html::csrfMetaTags() ?>
			<title><?= Html::encode(Yii::$app->view->title) ?></title>
			<?php Yii::$app->view->head() ?>
		</head>
		<body <?= Html::renderTagAttributes($attrs) ?> >
		<?php Yii::$app->view->beginBody();
	}
	
	static function endDraw() {
		Yii::$app->view->endBody() ?>
		</body>
		</html>
		<?php Yii::$app->view->endPage();
	}
	
	static function snippet($name, $from = null, $vars = []) {
		$from = !empty($from) ? $from : '@app';
		$fileName = $from . '/views/snippets/' . $name . '.php';
		return Yii::$app->view->renderFile($fileName, $vars);
	}

	private static function renderFile($fileName, $vars = []) {
		return Yii::$app->view->renderFile($fileName . '.php', $vars);
	}

}
