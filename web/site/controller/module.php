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
use system\model\Modules;
use system\model\ModuleSetting;
use system\model\User;
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
		if ( $action = q( 'get.a' ) ) {
			$info             = explode( '/', $action );
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
		} else {
			$this->module = q( "get.m" );
			v( 'module', Db::table( 'modules' )->where( 'name', $this->module )->first() );
		}
		//站点模块功能检测
		if ( empty( $this->module ) ) {
			message( '站点不存在这个模块,请系统管理员添加', 'back', 'error' );
		}
	}

	//模块主页
	public function home() {
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		$name   = q( 'get.m' );
		$module = Db::table( 'modules' )->where( 'name', $name )->first();
		foreach ( v( 'modules' ) as $v ) {
			if ( $v['name'] == q( 'get.m' ) ) {
				$moduleLinks = $v;
				break;
			}
		}
		v( 'module', $module );
		//分配菜单
		( new Menu() )->getMenus();
		View::with( '_site_modules_menu_', $moduleLinks );
		View::with( 'module', $module )->make();
	}

	//模块配置
	public function setting() {
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		//分配菜单
		( new Menu() )->getMenus();
		$class = '\addons\\' . $this->module . '\module';
		if ( ! class_exists( $class ) || ! method_exists( $class, 'settingsDisplay' ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		View::with( 'module_action_name', '参数设置' );
		$obj     = new $class();
		$setting = ( new ModuleSetting() )->getModuleConfig( $this->module );

		return $obj->settingsDisplay( $setting );
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

	//模块后台管理业务
	public function site() {
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		//分配菜单
		( new Menu() )->getMenus();
		//系统模块只存在name值,如果存在is_system等其他值时为插件扩展模块
		$class  = ( v( 'module.is_system' ) ? '\module\\' : '\addons\\' ) . $this->module . '\\' . $this->controller;
		$action = 'doSite' . $this->action;
		if ( ! class_exists( $class ) || ! method_exists( $class, $action ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		$obj = new $class();
		$obj->$action();
	}
}