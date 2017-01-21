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
	public function __construct() {
		auth();
	}

	//模块配置
	public function setting() {
		\User::loginAuth();
		moduleVerify();
		//后台分配菜单
		$class = '\addons\\' . v( 'module.name' ) . '\system\Config';
		if ( ! class_exists( $class ) || ! method_exists( $class, 'settingsDisplay' ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		View::with( 'module_action_name', '参数设置' );
		$obj     = new $class();
		$setting = \Module::getModuleConfig( v( 'module.name' ) );

		return $obj->settingsDisplay( $setting );
	}


	//模块封面
	public function cover() {
		$bid            = Request::get( 'bid' );
		$replyCover     = new ReplyCover();
		$module = Db::table( 'modules_bindings' )->where( 'bid', $bid )->first();
		if ( IS_POST ) {
			Validate::make( [
				[ 'title', 'required', '标题不能为空' ],
				[ 'description', 'required', '描述不能为空' ],
				[ 'thumb', 'required', '封面图片不能为空' ]
			] );
			$data             = json_decode( $_POST['keyword'], true );
			$data['rid']      = $replyCover->where( 'module', v( 'module.name' ) )->where( 'url', $module['do'] )->pluck( 'rid' );
			$data['module']   = 'cover';
			$data['rank']     = $data['istop'] == 1 ? 255 : min( 255, intval( $data['rank'] ) );
			$data['keywords'] = $data['keyword'];
			$rid              = service( 'WeChat' )->rule( $data );
			//添加封面回复
			$replyCover['id']          = $replyCover->where( 'rid', $rid )->pluck( 'id' );
			$replyCover['do']          = $module['do'];
			$replyCover['rid']         = $rid;
			$replyCover['title']       = $_POST['title'];
			$replyCover['description'] = $_POST['description'];
			$replyCover['thumb']       = $_POST['thumb'];
			$replyCover['url']         = $_POST['url'];
			$replyCover['module']      = v( 'module.name' );
			$replyCover->save();
			message( '功能封面更新成功', 'back', 'success' );
		}
		$field = Db::table( 'reply_cover' )->where( 'siteid', SITEID )->where( 'module', v( 'module.name' ) )->where( 'url', $module['do'] )->first();
		//获取关键词回复
		if ( $field ) {
			$data            = Db::table( 'rule' )->where( 'rid', $field['rid'] )->first();
			$data['keyword'] = Db::table( 'rule_keyword' )->orderBy( 'id', 'asc' )->where( 'rid', $field['rid'] )->get();
			View::with( 'rule', $data );
		}
		$field['url']  = __WEB__;
		$field['name'] = $module['title'];p($field);

		return view()->with( 'field', $field );
	}
}