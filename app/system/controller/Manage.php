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
use web\auth;

/**
 * 模块菜单和菜单组欢迎页
 * Class Home
 * @package core\controller
 */
class Manage {
	protected $user;

	public function __construct() {
		$this->user = new User();
	}

	//系统设置管理
	public function menu() {
		if ( ! $this->user->isLogin() ) {
			message( '请登录后进行操作', 'system/entry/login', 'error' );
		}
		View::with( 'isSuperUser', $this->user->isSuperUser() );
		View::make();
	}

	//更新缓存
	public function updateCache() {
		if ( ! $this->user->isSuperUser() ) {
			message( '只有系统管理员可以执行更新操作', 'back', 'error' );
		}
		if ( IS_POST ) {
			//更新数据缓存
			if ( isset( $_POST['data'] ) ) {
				Dir::del( 'storage/cache' );
			}
			//更新模板缓存
			if ( isset( $_POST['tpl'] ) ) {
				Dir::del( 'storage/view' );
			}
			//微信缓存
			if ( isset( $_POST['weixin'] ) ) {
				Dir::del( 'storage/weixin' );
			}
			//更新所有站点缓存
			m( 'Site' )->updateAllSiteCache();
			message( '缓存更新成功', 'back', 'success' );
		}

		View::make();
	}
}