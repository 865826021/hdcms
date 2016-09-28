<?php
//中间件配置
return [
	/**
	 * 全局中间件指在所有请求均会执行
	 */
	'global'     => [ 'system\middleware\Initialize' ],

	/**
	 * 普通中间件,可以在路由与控制器中开发者自行调用
	 */
	'middleware' => [

	],
];