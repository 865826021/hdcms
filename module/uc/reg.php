<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\uc;

use module\hdSite;
use system\model\Member;

class reg extends hdSite {
	protected $member;

	public function __construct() {
		parent::__construct();
		$this->member = new Member();
	}

	//注册页面
	public function doWebRegister() {
		if ( IS_POST ) {
			$data['mobile'] = $data['email'] = '';
			switch ( v( 'setting.register.item' ) ) {
				case 1:
					//手机号注册
					if ( ! preg_match( '/^\d{11}$/', $_POST['username'] ) ) {
						message( '请输入手机号', 'back', 'error' );
					}
					$_POST['mobile'] = $_POST['username'];
					break;
				case 2:
					//邮箱注册
					if ( ! preg_match( '/\w+@\w+/', $_POST['username'] ) ) {
						message( '请输入邮箱', 'back', 'error' );
					}
					$_POST['email'] = $_POST['username'];
					break;
				case 3:
					//二者都行
					if ( ! preg_match( '/^\d{11}$/', $_POST['username'] ) && ! preg_match( '/\w+@\w+/', $_POST['username'] ) ) {
						message( '请输入邮箱或手机号', 'back', 'error' );
					} else if ( preg_match( '/^\d{11}$/', $_POST['username'] ) ) {
						$_POST['mobile'] = $_POST['username'];
					} else {
						$_POST['email'] = $_POST['username'];
					}
			}

			if ( $this->member->where( 'email', $_POST['username'] )->orWhere( 'mobile', $_POST['username'] )->get() ) {
				message( '用户名已经存在', 'back', 'error' );
			}
			if ( ! $this->member->add() ) {
				message( $this->member->getError(), 'back', 'error' );
			}
			message( '恭喜你,注册成功!系统将跳转到登录页面', web_url( 'login' ), 'success' );
		}
		View::with( 'placeholder', v( 'setting.register.item' ) == 1 ? '手机号' : ( v( 'setting.register.item' ) == 2 ? '邮箱' : '手机号/邮箱' ) );
		View::make( $this->ucenter_template .'/register.html' );
	}

	//登录
	public function doWebLogin() {
		if ( IS_POST ) {
			if ( ! $this->member->login() ) {
				message( $this->member->getError(), 'back', 'error' );
			}
			$backurl = q( 'get.backurl', web_url( 'entry/home', [ 'siteid' => SITEID ] ), 'htmlentities' );
			message( '登录成功', $backurl, 'success' );
		}
		//微信自动登录
		if ( IS_WEIXIN && v( 'wechat.level' ) >= 3 && v( 'setting.register.focusreg' ) == 1 ) {
			if ( $this->member->loginByOpenid() ) {
				$url = q( 'get.backurl', web_url( 'entry/home', [ 'siteid' => SITEID ] ) );
				go( $url );
			}
		}
		View::make( $this->ucenter_template .'/login.html' );
	}

	//使用微信openid登录
	public function doWEbOpenidLogin() {
		//微信自动登录
		if ( IS_WEIXIN && v( 'wechat.level' ) >= 3) {
			if ( $this->member->loginByOpenid() ) {
				$url = q( 'get.backurl', web_url( 'entry/home', [ 'siteid' => SITEID ] ) );
				go( $url );
			}
		}
		message( '微信登录失败', 'back', 'error' );
	}

	//退出
	public function doWEbOut() {
		Session::flush();
		message( '退出成功', web_url( 'login' ), 'success', 1 );
	}

	//找回密码
	public function doWEbRetrieve() {

	}
}