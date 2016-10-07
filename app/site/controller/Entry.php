<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace app\site\controller;
/**
 * 后台站点管理入口
 * Class entry
 * @package site\controller
 * @author 向军
 */
class Entry {
	public function __construct() {
		service( 'user' )->loginAuth();
		//验证站点权限
		if ( ! service( 'user' )->isOperate() ) {
			message( '你没有管理站点的权限', 'system/site/lists', 'warning' );
		}

	}

	//进入后台站点管理入口
	public function refer() {
		//获取系统菜单
		$menu = service( 'menu' )->menus();
		$cur  = current( $menu );
		go( __ROOT__ . $cur['url'] );
	}

	//顶级菜单主页
	public function home() {
		//分配菜单
		service( 'menu' )->assign();

		return view( VIEW_PATH . '/home/' . q( 'get.p' ) . '.php' );
	}

	//返回模块列表功能
	public function package() {
		//分配菜单
		service( 'menu' )->assign();

		//分配菜单
		return view();
	}
}