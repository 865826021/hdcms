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
	/**
	 * 执行模块动作
	 * 动作分为系统动作与控制器动作
	 */
	public function action() {
		$info       = explode( '/', Request::get( 'action' ) );
		$controller = ucfirst( $info[0] );
		$action     = $info[1];
		$class      = ( v( 'module.is_system' ) ? "module\\" : "addons\\" ) .
		              v( 'module.name' ) . '\controller\\' . $controller;

		return call_user_func_array( [ new $class(), $action ], [ ] );
	}

//	//进入后台站点管理入口
//	public function refer() {
//		//获取系统菜单
//		$menu = \Menu::all();
//		$cur  = current( $menu );
//		go( __ROOT__ . $cur['url'] );
//	}

	/**
	 * 站点入口管理主页
	 * @return mixed
	 */
	public function home() {
		\User::isOperate();
		if ( ! $p = Request::get( 'p' ) ) {
			//获取系统菜单
			$menu = \Menu::all();
			if ( empty( $menu ) ) {
				message( '站点没有可访问的模块', 'back', 'error' );
			}
			$cur = current( $menu );
			go( __ROOT__ . $cur['url'] );
		}

		return view( VIEW_PATH . '/home/' . $p . '.php' );
	}

	/**
	 * 后台模块主页
	 * @return mixed
	 */
	public function module() {
		\User::moduleVerify();

		return view();
	}

//	//返回模块列表功能
//	public function package() {
//		//分配菜单
//		return view();
//	}
}