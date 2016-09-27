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
 * 应用商店管理
 * Class store
 * @package system\controller
 * @author 向军
 */
class Store {

	public function __construct() {
		if ( ! ( new User() )->isLogin() ) {
			message( '你还没有登录,无法进行操作', 'system/entry/login', 'warning' );
		}
	}

	//模块菜单
	public function module() {
		message( '嗨. 不要着急, 正在开发中...', 'back', 'info' );
	}
}