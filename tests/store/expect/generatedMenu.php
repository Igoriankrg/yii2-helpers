<?php 

return [
	[
		'label' => 'Title',
		'icon' => '<i class="fa fa-cube"></i>',
		'items' => [
			[
				'label' => 'List',
				'url' => '/vendor/info/list',
				'icon' => '<i class="fa fa-circle-o "></i>',
				'active' => true,
			],
			[
				'label' => 'List changed',
				'url' => '/vendor/info/list-changed',
				'icon' => '<i class="fa fa-circle-o "></i>',
				'active' => false,
			],
			[
				'label' => 'List for release',
				'url' => '/vendor/info/list-for-release',
				'icon' => '<i class="fa fa-circle-o "></i>',
				'active' => false,
			],
		],
		'url' => '/#',
		'active' => true,
	],
	[
		'label' => 'Rbac',
		'icon' => '<i class="fa fa-user-o"></i>',
		'items' => [
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
		],
		'url' => '/#',
		'active' => false,
	],
];