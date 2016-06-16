<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\system\controller;

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
		if ( ! $this->user->isLogin() ) {
			message( '请登录后进行操作', 'back', 'error' );
		}
	}

	//系统设置管理
	public function menu() {
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
				//更新站点缓存
				$sites = Db::table( 'site' )->get();
				foreach ( $sites as $s ) {
					api( 'site' )->updateSiteCache( $s['siteid'] );
				}
				//删除缓存数据
				Dir::del( 'storage/cache' );
			}
			//更新模板缓存
			if ( isset( $_POST['tpl'] ) ) {
				Dir::del( 'storage/view' );
			}
			message( '缓存更新成功', 'back', 'success' );
		}

		View::make();
	}
}