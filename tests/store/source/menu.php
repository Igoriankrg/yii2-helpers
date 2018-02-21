<?php 

return [
	[
		//'module' => 'vendor',
		//'access' => PermissionEnum::VENDOR_MANAGE,
		'label' => ['vendor/main', 'title'],
		'icon' => 'cube',
		'items' => [
			[
				'label' => ['vendor/info', 'list'],
				'url' => 'vendor/info/list',
				'icon' => 'circle-o ',
				'active' => true,
			],
			[
				'label' => ['vendor/info', 'list_changed'],
				'url' => 'vendor/info/list-changed',
				'icon' => 'circle-o ',
			],
			[
				'label' => ['vendor/info', 'list_for_release'],
				'url' => 'vendor/info/list-for-release',
				'icon' => 'circle-o ',
			],
		],
	],
	[
		'label' => ['admin', 'rbac'],
		//'module' => 'rbac',
		'icon' => 'user-o',
		'items' => [
			[
				'label' => ['admin', 'rbac_permission'],
				'url' => 'rbac/permission',
			],
			[
				'label' => ['admin', 'rbac_role'],
				'url' => 'rbac/role',
			],
			[
				'label' => ['admin', 'rbac_rule'],
				'url' => 'rbac/rule',
			],
			[
				'label' => ['admin', 'rbac_assignment'],
				'url' => 'rbac/assignment',
			],
		],
	]
];