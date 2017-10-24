<?php

namespace yii2lab\helpers\yii;

use Yii;
use yii\helpers\Html as YiiHtml;

class Html extends YiiHtml
{
	const WEB_NO_IMAGE = '@web/images/image/no_image.png';

	public static function getDataUrl($fileName) {
		$fileName = FileHelper::normalizePath($fileName);
		if(FileHelper::has($fileName)) {
			$content = FileHelper::load($fileName);
			$mimeType = FileHelper::getMimeType($fileName);
			$base64code = 'data:'.$mimeType.';base64, ' . base64_encode($content);
			return $base64code;
		}
		return Yii::getAlias(self::WEB_NO_IMAGE);
	}

	public static function fa($icon, $options = [], $prefix = 'fa fa-', $tag = 'i')
	{
		return self::icon($icon, $options, 'fa fa-', $tag);
	}
	
	public static function icon($icon, $options = [], $prefix = 'fa fa-', $tag = 'i')
	{
		if(!is_array($options)) {
			$type = $options;
			$options = [];
			$options['class'] = $type ? ' text-' . $type : '';
		} else {
			$options['class'] = !empty($options['class']) ? $options['class'] : '';
		}
		
		$options['class'] = $prefix . $icon . ' ' . $options['class'];
		return static::tag($tag, '', $options);
	}

}
