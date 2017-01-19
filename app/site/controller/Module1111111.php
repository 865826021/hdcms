<?php namespace app\site\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\ReplyCover;
/**
 * 模块动作访问处理
 * Class Module
 * @package site\controller
 */
class Module {
	//指控制器目录
	protected $module;
	//控制器
	protected $controller;
	//动作
	protected $action;

	public function __construct() {
		//站点模块功能检测
		if ( ! v( 'module' ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		if ( $action = q( 'get.a' ) ) {
			$info             = explode( '/', $action );
			$this->module     = count( $info ) == 3 ? array_shift( $info ) : NULL;
			$this->controller = $info[0];
			$this->action     = $info[1];
		} else {
			$this->module = v( 'module.name' );
		}
	}

	//模块主页
	public function home() {
		if ( ! service( 'module' )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		//后台分配菜单
		service( 'menu' )->assign();

		return view();
	}

	//模块配置
	public function setting() {
		service( 'user' )->loginAuth();
		if ( ! service( 'module' )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		//后台分配菜单
		service( 'menu' )->assign();
		$class = '\addons\\' . v( 'module.name' ) . '\module';
		if ( ! class_exists( $class ) || ! method_exists( $class, 'settingsDisplay' ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		View::with( 'module_action_name', '参数设置' );
		$obj     = new $class();
		$setting = service('module')->getModuleConfig( v( 'module.name' ) );

		return $obj->settingsDisplay( $setting );
	}


	//模块封面设置
	public function cover() {
		//验证登录
		service( 'user' )->loginAuth();
		//后台分配菜单
		service( 'menu' )->assign();
		if ( ! service( 'module' )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		$bid            = Request::get( 'bid' );
		$replyCover     = new ReplyCover();
		$moduleBindings = Db::table( 'modules_bindings' )->where( 'bid', $bid )->first();
		if ( IS_POST ) {
			Validate::make( [
				[ 'title', 'required', '标题不能为空' ],
				[ 'description', 'required', '描述不能为空' ],
				[ 'thumb', 'required', '封面图片不能为空' ]
			] );
			$data             = json_decode( $_POST['keyword'], TRUE );
			$data['rid']      = $replyCover->where( 'module', v( 'module.name' ) )->where( 'do', $moduleBindings['do'] )->pluck( 'rid' );
			$data['module']   = 'cover';
			$data['rank']     = $data['istop'] == 1 ? 255 : min( 255, intval( $data['rank'] ) );
			$data['keywords'] = $data['keyword'];
			$rid              = service( 'WeChat' )->rule( $data );
			//添加封面回复
			$replyCover['id']          = $replyCover->where( 'rid', $rid )->pluck( 'id' );
			$replyCover['do']          = $moduleBindings['do'];
			$replyCover['rid']         = $rid;
			$replyCover['title']       = $_POST['title'];
			$replyCover['description'] = $_POST['description'];
			$replyCover['thumb']       = $_POST['thumb'];
			$replyCover['url']         = $_POST['url'];
			$replyCover['module']      = v( 'module.name' );
			$replyCover->save();
			message( '功能封面更新成功', 'back', 'success' );
		}
		$field = $replyCover->where( 'siteid', SITEID )->where( 'module', v( 'module.name' ) )->where( 'do', $moduleBindings['do'] )->first();
		//获取关键词回复
		if ( $field ) {
			$data            = Db::table( 'rule' )->where( 'rid', $field['rid'] )->first();
			$data['keyword'] = Db::table( 'rule_keyword' )->orderBy( 'id', 'asc' )->where( 'rid', $field['rid'] )->get() ?: [ ];
			View::with( 'rule', $data );
		}
		$field['url']  = '?a=site/' . $moduleBindings['do'] . "&siteid=" . SITEID . "&t=web&m=" . v( 'module.name' );
		$field['name'] = $moduleBindings['title'];

		return view()->with( 'field', $field );
	}

	//请求入口
	public function entry() {
		switch ( q( 'get.t' ) ) {
			case 'site':
				return $this->site();
			case 'web':
				return $this->web();
			default:
				message( '你访问的页面不存在', 'back', 'warning' );
		}
	}
}