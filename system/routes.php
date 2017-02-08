<?php
/*--------------------------------------------------------------------------
| 路由规则设置
|--------------------------------------------------------------------------
| 框架支持路由访问机制与普通GET方式访问
| 如果使用普通GET方式访问时不需要设置路由规则
| 当然也可以根据业务需要两种方式都使用
|-------------------------------------------------------------------------*/
//后台管理员登录
Route::get( 'hdcms', function () {
	Session::set( 'system.login', 'hdcms' );
//	Route
//	go( u( 'system/entry/login' ) );
} );

/**
 * 站点管理员登录
 * 站点管理员登录后直接登录到站点管理平台
 * 不显示系统管理界面
 */
Route::get( 'admin', function () {
	Session::set( 'system.login', 'admin' );
	$site = Db::table( 'site' )->where( 'domain', $_SERVER['SERVER_NAME'] )->first();
	go( u( 'system/entry/login', [ 'from' => __ROOT__ . '?s=site/entry/home&siteid=' . $site['siteid'] ] ) );
} );