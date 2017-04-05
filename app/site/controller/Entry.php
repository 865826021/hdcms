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

use houdunwang\request\Request;

/**
 * 网站入口管理
 * Class Entry
 * @package app\site\controller
 */
class Entry {
	/**
	 * 通过域名访问时执行的方法
	 * 首先根据域名判断该域名是否是站点的默认域名
	 * 然后通过域名执行默认模块
	 * @return mixed
	 */
	public function index() {
		$siteid = Request::get( 'siteid' );
		$module = v( 'module.name' );
		if ( $siteid && $module ) {
			//站点设置了默认访问模块时访问模块的桌面入口页面
			$module = Db::table( 'modules_bindings' )
			            ->join( 'modules', 'modules.name', '=', 'modules_bindings.module' )
			            ->join( 'module_domain', 'module_domain.module', '=', 'modules.name' )
			            ->where( 'module_domain.siteid', $siteid )
			            ->where( 'modules.name', $module )
			            ->where( 'entry', 'web' )->first();
			if ( $module && ! empty( $module['do'] ) ) {
				$class = ( $module['is_system'] ? 'module' : 'addons' ) . '\\' . $module['module'] . '\system\Navigate';
				if ( class_exists( $class ) && method_exists( $class, $module['do'] ) ) {
					return call_user_func_array( [ new $class, $module['do'] ], [] );
				}
			}
		}

		return view();
	}

	/**
	 * 执行模块动作
	 * 动作分为系统动作与控制器动作
	 */
	public function action() {
		$info       = explode( '/', Request::get( 'action' ) );
		$module     = strtolower( $info[0] );
		$controller = ucfirst( $info[1] );
		$action     = $info[2];
		$class      = ( v( 'module.is_system' ) ? "module\\" : "addons\\" ) .
		              v( 'module.name' ) . "\\{$module}\\{$controller}";

		return call_user_func_array( [ new $class(), $action ], [] );
	}

	/**
	 * 站点入口管理主页
	 * @return mixed
	 */
	public function home() {
		auth();
		if ( ! $mark = Request::get( 'mark' ) ) {
			//获取系统菜单
			$menu = \Menu::getSystemMenu();
			if ( empty( $menu ) ) {
				message( '站点没有可访问的模块', 'back', 'error' );
			}
			$current = current( $menu );
			$mark    = $current['mark'];
			Request::set( 'get.mark', $mark );
		}

		return view( VIEW_PATH . '/entry/home/' . $mark . '.php' );
	}

	/**
	 * 扩展功能模块
	 * @return mixed
	 */
	public function package() {
		auth();
		$data = Module::getBySiteUser();

		return view()->with( [ 'data' => $data ] );
	}

	/**
	 * 后台模块主页
	 * @return mixed
	 */
	public function module() {
		auth();

		return view();
	}
}