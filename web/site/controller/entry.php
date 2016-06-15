<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace site\controller;

//后台站点管理入口
class entry {
	public function __construct() {
		//验证站点权限
		if ( api( 'site' )->siteVerify() === FALSE ) {
			message( '你没有管理站点的权限', u( "system/site/lists" ), 'warning' );
		}
	}

	//进入站点管理入口
	public function refer() {
		//获取系统菜单
		$menu = api( 'menu' )->getSystemMenus();
		if ( ! $menu ) {
			message( '你没有管理站点的权限', u( "system/site/lists" ), 'warning' );
		}
		$cur = current( $menu );
		go( __ROOT__ . $cur['url'] );
	}

	//后台顶级菜单入口
	public function __empty() {
		//分配菜单
		api( 'menu' )->assignMenus();
		View::make( VIEW_PATH . '/home/' . ACTION . '.php' );
	}
}