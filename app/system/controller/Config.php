<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace app\system\controller;

use system\model\Config as ConfigModel;

/**
 * 系统配置管理
 * Class Config
 * @package system\controller
 */
class Config {
	public function __construct() {
		\User::superUserAuth();
	}

	//注册配置管理
	public function register() {
		if ( IS_POST ) {
			$model             = ConfigModel::first();
			$model['register'] = Request::post( 'register' );
			$model->save();
			message( '注册设置保存成功', 'with' );
		}
		View::with( 'group', Db::table( 'user_group' )->get() );
		View::with( 'field', v( 'config.register' ) );

		return view();
	}

	//站点开/关设置
	public function site() {
		if ( IS_POST ) {
			$model         = ConfigModel::first();
			$model['site'] = Request::post( 'site' );
			$model->save();
			message( '站点设置更新成功', 'with' );
		}

		return view()->with( 'field', v( 'config.site' ) );
	}
}