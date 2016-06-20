<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\site\controller;

use system\model\Menu;
use system\model\UserPermission;

/**
 * 模块动作访问处理
 * Class Module
 * @package site\controller
 */
class Module {
	protected $module;
	protected $controller;
	protected $action;

	public function __construct() {
		$info             = explode( '/', q( 'get.a' ) );
		$this->module     = empty( $info[0] ) ? '' : $info[0];
		$this->controller = empty( $info[1] ) ? '' : $info[1];
		$this->action     = empty( $info[2] ) ? '' : $info[2];
		$module           = Db::table( 'modules' )->where( 'name', $this->module )->first();
		if ( empty( $module ) ) {
			//系统模块
			if ( is_dir( 'module/' . $this->module ) ) {
				$module['name']      = $this->module;
				$module['is_system'] = 1;
			}
		}
		if ( ! empty( $module ) && is_array( $module ) ) {
			v( 'module', $module );
		}
	}

	//请求入口
	public function entry() {
		switch ( q( 'get.t' ) ) {
			case 'site':
				$this->site();
				break;
			case 'web':
				$this->web();
				break;
			default:
				message( '你访问的页面不存在', 'back', 'warning' );
		}
	}

	//模块前台处理业务
	public function web() {
		$action = 'doWeb' . $this->action;
		$class  = ( v( 'module.is_system' ) ? '\module\\' : '\addons\\' ) . $this->module . '\\' . $this->controller;
		if ( class_exists( $class ) && method_exists( $class, $action ) ) {
			$obj = new $class();

			return $obj->$action();
		}
	}

	//模块主页
	public function home() {
		//验证站点权限
		if ( ( new UserPermission() )->isOperate() === FALSE ) {
			message( '你没有管理模块的权限', 'back', 'error' );
		}
		//分配菜单
		( new Menu() )->getMenus(  );
		$module = Db::table( 'modules' )->where( 'name', q( 'get.m' ) )->first();
		View::with( 'module', $module )->make();
	}

	//模块配置
	public function setting() {
		//验证站点权限
		if ( api( 'site' )->siteVerify() === FALSE ) {
			message( '你没有设置模块配置项的权限', u( 'site/entry/refer', [ 'siteid' => v( "site.siteid" ) ] ), 'error' );
		}
		//分配菜单
		api( 'menu' )->assignMenus();
		$class = '\addons\\' . $this->module . '\module';
		if ( ! class_exists( $class ) ) {
			message( '访问的模块不存在', u( 'site/entry/refer', [ 'siteid' => v( "site.siteid" ) ] ), 'error' );
		}
		$setting = Db::table( 'module_setting' )
		             ->where( 'module', '=', $this->module )
		             ->where( 'siteid', '=', v( 'site.siteid' ) )
		             ->pluck( 'setting' );
		$obj     = new $class();

		return $obj->settingsDisplay( unserialize( $setting ) );
	}

	//模块后台管理业务
	public function site() {
		//验证站点权限
		if ( ( new UserPermission() )->isOperate() === FALSE ) {
			message( '你没有管理模块的权限', 'back', 'error' );
		}
		//分配菜单
		( new Menu() )->getMenus(  );
		//系统模块只存在name值,如果存在is_system等其他值时为插件扩展模块
		$class  = ( v( 'module.is_system' ) ? '\module\\' : '\addons\\' ) . $this->module . '\\' . $this->controller;
		$action = 'doSite' . $this->action;
		if ( ! class_exists( $class ) || ! method_exists( $class, $action ) ) {
			message( '访问的模块不存在', u( 'site/entry/refer', [ 'siteid' => v( "site.siteid" ) ] ), 'error' );
		}
		$obj = new $class();
		$obj->$action();
	}
}