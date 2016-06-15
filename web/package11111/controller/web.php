<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace package\controller;

/**
 * 用于模块前台处理入口
 * Class web
 * @package platform\controller
 * @author 向军
 */
class web {
	//站点编号
	protected $site;

	//模块名称
	protected $module;

	//构造函数
	public function __construct() {
		$this->siteid = q( 'get.i', 0, 'intval' );
		$this->module = q( 'get.m', 0, 'htmlspecialchars' );
		api( 'module' )->siteHasExeModule( $this->module );
	}

	//移动端执行执行
	public function entry() {
		$controller = q( 'get.c', 'site', 'htmlspecialchars' );
		//使用模块动作编号访问
		if ( $bid = q( 'get.bid', 0, 'intval' ) ) {
			$bind         = Db::table( 'modules_bindings' )->where( 'bid', $bid )->first();
			$this->module = $bind['module'];
			$do           = 'doWeb' . $bind['do'];
			//验证访问权限
			api( 'module' )->auth( 'hd_' . $bind['do'] );
		} else {
			//直接传递执行动作时
			$do = 'doWeb' . q( 'get.do', 0, 'htmlspecialchars' );
		}
		//加载站点缓存
		System::loadSite( $this->siteid );
		Session::set( 'siteid', $this->siteid );
		$class = '\addons\\' . $this->module . '\\' . $controller;
		if ( class_exists( $class ) ) {
			$obj = new $class( $this->module );
			$obj->$do();
		}
	}
}