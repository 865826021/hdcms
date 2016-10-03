<?php namespace app\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\User;

/**
 * 系统配置管理
 * Class Config
 * @package system\controller
 */
class Config {
	public function __construct() {
		if ( ! ( new User() )->isSuperUser() ) {
			message( '只有系统管理员可以操作', 'back', 'error' );
		}
	}

	//注册配置管理
	public function register() {
		$Config = new \system\model\Config();

		if ( IS_POST ) {
			$Config->id       = 1;
			$Config->register = Request::post( 'register' );
			$Config->save();
			message( '保存成功', 'back', 'success' );
		}
		View::with( 'group', Arr::string_to_int( Db::table( 'user_group' )->get() ) );
		View::with( 'field', Arr::string_to_int( $Config->getByName( 'register' ) ) );

		return view();
	}

	//站点开/关设置
	public function site() {
		$Config = new \system\model\Config();
		if ( IS_POST ) {
			$Config->id   = 1;
			$Config->site = Request::post( 'site' );
			$Config->save();
			message( '保存成功', 'back', 'success' );
		}

		return view()->with( 'field', Arr::string_to_int( $this->db->getByName( 'site' ) ) );
	}
}