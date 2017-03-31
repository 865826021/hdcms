<?php
/*--------------------------------------------------------------------------
| 路由规则设置
|--------------------------------------------------------------------------
| 框架支持路由访问机制与普通GET方式访问
| 如果使用普通GET方式访问时不需要设置路由规则
| 当然也可以根据业务需要两种方式都使用
|-------------------------------------------------------------------------*/
//微信支付异步通知地址
Route::any( 'wxnotifyurl', 'site/pay/weChatAsyncNotify' );

//更新HDCMS
Route::any( 'update', 'system/cloud/localUpdate' );

//后台管理员登录
Route::alias( 'login', 'm=ucenter&action=controller/entry/login' );

//后台管理员登录
Route::any( 'hdcms', function () {
	Session::set( 'system.login', 'hdcms' );
	action( 'system.entry.login' );
} );

/**
 * 站点管理员登录
 * 站点管理员登录后直接登录到站点管理平台
 * 不显示系统管理界面
 */
Route::any( 'admin', function () {
	Session::set( 'system.login', 'admin' );
	action( 'system.entry.login' );
} );

/**
 * 文章模块路由规则
 */
Route::alias( 'article{siteid}-{cid}-{page}.html',
	'm=article&action=controller/entry/category&siteid={siteid}&cid={cid}&page={page}' )
     ->where( [
	     'siteid' => '\d+',
	     'cid'    => '\d+',
	     'page'   => '\d+'
     ] );
Route::alias( 'article{siteid}-{aid}-{cid}-{mid}.html',
	'm=article&action=controller/entry/content&siteid={siteid}&cid={cid}&aid={aid}&mid={mid}' )
     ->where( [
	     'siteid' => '\d+',
	     'aid'    => '\d+',
	     'cid'    => '\d+',
	     'mid'    => '\d+'
     ] );;