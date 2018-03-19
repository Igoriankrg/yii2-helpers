<?php

return [
	'bootstrap' => ['log', 'language', 'queue'],
	'timeZone' => 'UTC',
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
];
