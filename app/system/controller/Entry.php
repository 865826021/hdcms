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

use system\model\Config;
use system\model\User;

/**
 * 后台登录/退出
 * Class entry
 * @package system\controller
 */
class Entry {
	//注册
	public function register() {
		//站点关闭检测
		checkSiteClose();
		$config         = new Config();
		$User           = new User();
		$registerConfig = $config->getByName( 'register' );
		if ( v( 'system.site.is_open' ) == 0 ) {
			message( '网站暂时关闭注册', 'back', 'error' );
		}
		if ( IS_POST ) {
			if ( ! Code::auth( 'code' ) ) {
				message( '验证码输入错误', 'back', 'error' );
			}
			//默认用户组
			$User['groupid'] = v( 'system.register.groupid' );
			$User['status']  = v( 'system.register.audit' );
			if ( ! $User->save() ) {
				message( $User->getError(), 'back', 'error' );
			}
			message( '注册成功,请登录系统', u( 'login', [ 'from' => $_GET['from'] ] ) );
		}

		return view()->with( 'registerConfig', $registerConfig );
	}

	/**
	 * 后台帐号登录
	 * @return mixed
	 */
	public function login() {
		$User = new User();
		if ( IS_POST ) {
			Validate::make( [
				[ 'username', 'required', '用户名不能为空', 3 ],
				[ 'password', 'required', '请输入帐号密码', 3 ],
				[ 'code', 'captcha', '验证码输入错误', 3 ],
			] );

			if ( ! $User->login( Request::post() ) ) {
				message( $User->getError(), 'back', 'error' );
			}
			//系统管理员忽略网站关闭检测
			if ( ! $User->isSuperUser( NULL, 'return' ) ) {
				checkSiteClose();
			}

			message( '登录成功,系统准备跳转', q( 'get.from', u( 'system/site/lists' ) ) );
		}
		if ( Session::get( 'user.uid' ) ) {
			go( 'system/site/lists' );
		}
		$Config = new Config();

		return view()->with( 'siteConfig', $Config->getByName( 'site' ) );
	}

	//验证码
	public function code() {
		Code::make();
	}

	//退出
	public function quit() {
		Session::flush();
		message( '退出系统成功,系统将自动进行跳转', q( 'get.from', u( 'login' ) ) );
	}
}