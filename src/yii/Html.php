<?php

namespace yii2lab\helpers\yii;

use Yii;
use yii\helpers\Html as YiiHtml;

class Html extends YiiHtml
{

    public static function defineFont($font, $path, $extList = ['ttf']) {
        $typesCss = '';
        foreach($extList as $ext) {
            if($ext == 'eot') {
                $typesCss .= '
                    src: url("'.$path.'.eot");
                    src: url("'.$path.'.eot?#iefix")format("embedded-opentype"),';
            }
            if($ext == 'woff') {
                $typesCss .= '
                    url("'.$path.'.woff") format("woff"),';
            }
            if($ext == 'ttf') {
                $typesCss .= '
                    url("'.$path.'.ttf") format("truetype")';
            }
        }
        $css = '
            @font-face {
                font-family: "'.$font.'";
                '.$typesCss.';
            }
        ';
        Yii::$app->view->registerCss($css);
    }

    public static function setFont($fontName) {
        $css = '
            html, body, h1, h2, h3, h4, h5, h6
            {
                font-family: \''.$fontName.'\';
            }
        ';
        Yii::$app->view->registerCss($css);
    }

    public static function recursiveHtmlEntities($val) {
        if(is_object($val)) {
            $val = (array) $val;
        }
        if(is_array($val)) {
            $closure = function($v) {
                if( ! is_array($v) && ! is_object($v)) {
                    $v = htmlentities($v);
                }
                return $v;
            };
            $val = ArrayHelper::recursiveIterator($val, $closure);
        } else {
            $val = htmlentities($val);
        }
        return $val;
    }

	public static function getDataUrl($fileName) {
		$fileName = FileHelper::normalizePath($fileName);
		if(!FileHelper::has($fileName)) {
			return null;
		}
		$content = FileHelper::load($fileName);
		$mimeType = FileHelper::getMimeType($fileName);
		$base64code = 'data:'.$mimeType.';base64, ' . base64_encode($content);
		return $base64code;
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
