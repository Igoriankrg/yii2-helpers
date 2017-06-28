<?php

use yii2lab\helpers\Debug;
use yii\helpers\ArrayHelper;

function prr($val, $exit = false, $forceToArray = false) {
	if(!empty($forceToArray)) {
		$val = ArrayHelper::toArray($val);
	}
	Debug::varDump($val, $exit);
}

function mb_ucfirst ($word)
{
	return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word), 'UTF-8');
}

function sortByLen($a, $b)
{
  if (strlen($a) < strlen($b)) {
  return 1;
  }
  elseif (strlen($a) == strlen($b)) {
  return 0;
  }
  else { return -1; }
}
