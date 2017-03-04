<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\middleware;

use houdunwang\framework\build\Provider;

class MiddlewareProvider extends Provider {
	//延迟加载
	public $defer = false;

	public function boot() {
		//控制器访问时控制器或方法不存在时执行的中间件
		c( 'middleware.system.controller_not_found', [ 'houdunwang\framework\middleware\ControllerNotFound' ] );
		c( 'middleware.system.action_not_found', [ 'houdunwang\framework\middleware\ActionNotFound' ] );
		//路由规则没有匹配时执行
		c( 'middleware.system.router_not_found', [ 'houdunwang\framework\middleware\RouterNotFound' ] );
		//csrf表单令牌验证
		c( 'middleware.system.csrf_validate', [ 'houdunwang\framework\middleware\Csrf' ] );
		//分配表单验证失败信息
		c( 'middleware.system.form_validate', [ 'houdunwang\framework\middleware\Validate' ] );
		//执行全局中间件
		\Middleware::globals();
		\Middleware::system( 'csrf_validate' );
		\Middleware::system( 'form_validate' );
	}

	public function register() {
		$this->app->single( 'Middleware', function () {
			return Middleware::single();
		} );
	}


}