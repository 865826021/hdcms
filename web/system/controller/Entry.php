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

use system\model\Config;
use system\model\User;

/**
 * 后台登录/退出
 * Class entry
 * @package system\controller
 */
class Entry {
	protected $db;

	public function __construct() {
		$this->db = new User();
	}

	//注册
	public function register() {
		//站点关闭检测
		checkSiteClose();
		$config         = new Config();
		$registerConfig = $config->getByName( 'register' );
		if ( $registerConfig['is_open'] == 0 ) {
			message( '网站暂时关闭注册', 'back', 'error' );
		}
		if ( IS_POST ) {
			if ( isset( $_POST['code'] ) && strtoupper( $_POST['code'] ) != Code::get() ) {
				message( '验证码输入错误', 'back', 'error' );
			}
			//默认用户组
			$_POST['groupid'] = $registerConfig['groupid'];
			$_POST['status']  = $registerConfig['audit'] == 1 ? 0 : 1;
			if ( ! $this->db->add() ) {
				message( $this->db->getError(), 'back', 'error' );
			}
			message( '注册成功,请登录系统', 'login' );
		}
		View::with( 'registerConfig', $registerConfig );
		View::make();
	}

	//登录
	public function login() {
		$Config = new Config();
		if ( IS_POST ) {
			if ( ! $this->db->login( q( 'post.' ) ) ) {
				message( $this->db->getError(), 'back', 'error' );
			}
			//站点关闭检测/系统管理员忽略网站关闭检测
			if ( Session::get( 'user.uid' ) != 1 ) {
				checkSiteClose();
			}
			message( '登录成功,系统准备跳转到后台', 'system/site/lists' );
		}
		if ( Session::get( 'user.uid' ) ) {
			go( 'system/site/lists' );
		}
		View::with( 'siteConfig', $Config->getByName( 'site' ) );
		View::make();
	}

	//验证码
	public function code() {
		Code::make();
	}

	//退出
	public function quit() {
		session_unset();
		session_destroy();
		message( '退出系统成功,系统将自动进行跳转', u( 'login' ) );
	}
}