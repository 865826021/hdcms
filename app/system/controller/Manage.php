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

use system\model\Site;
use system\model\User;

/**
 * 模块菜单和菜单组欢迎页
 * Class Manage
 * @package app\system\controller
 */
class Manage {
	/**
	 * 系统设置管理
	 * @return mixed
	 */
	public function menu() {
		$User = new User();
		$User->isLogin();

		return view()->with( 'isSuperUser', $User->isSuperUser( v( 'user.uid' ), 'return' ) );
	}

	/**
	 * 更新缓存
	 * @return mixed
	 */
	public function updateCache() {
		$User = new User();
		$Site = new Site();
		if ( ! $User->isSuperUser() ) {
			message( '只有系统管理员可以执行更新操作', 'back', 'error' );
		}
		if ( IS_POST ) {
			//更新数据缓存
			if ( isset( $_POST['data'] ) ) {
				Dir::del( ROOT_PATH . '/storage/cache' );
			}
			//更新模板缓存
			if ( isset( $_POST['tpl'] ) ) {
				Dir::del( ROOT_PATH . '/storage/view' );
			}
			//微信缓存
			if ( isset( $_POST['weixin'] ) ) {
				Dir::del( ROOT_PATH . '/storage/weixin' );
			}
			//更新所有站点缓存
			$Site->updateAllSiteCache();
			message( '缓存更新成功', 'back', 'success' );
		}

		return view();
	}
}