<?php
//中间件配置
return [
<<<<<<< HEAD
	/**
	 * 控制器中间件指在控制器中执行的中间件
	 */
	'controller' => [ ],

	/**
	 * 中间件列表
	 */
	'middleware' => [
		//应用开始时执行的中间件
		'app_start' => [
			'system\middleware\Initialize',
			'hdphp\middleware\build\AppStart'
		],
		//应用结束后执行的中间件
		'app_end'   => [
			'hdphp\middleware\build\AppEnd'
		]
	]
=======
    //全局中间件
    'global'     => [ ],
    //路由中间件
    'middleware' => [],
>>>>>>> d191ef7fd2db8f578f2e53e41b319d65713d9c79
];