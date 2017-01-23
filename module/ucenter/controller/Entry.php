<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace module\ucenter\controller;


use module\HdController;

/**
 * 会员登录注册管理
 * Class Entry
 * @package module\ucenter\controller
 */
class Entry extends HdController {
	public function __construct() {
		parent::__construct();
		$this->template = "ucenter/" . v( 'site.info.ucenter_template' ) . '/' . ( IS_MOBILE ? 'mobile' : 'web' );
		template_path( $this->template );
		template_url( __ROOT__ . '/' . $this->template );
		//来源页面
		if ( $from = Request::get( 'from' ) ) {
			Session::set( 'from', $from );
		}
	}

	//注册页面
	public function register() {
		if ( IS_POST ) {
			\Member::register( Request::post() );
			$this->login();
		}
		$placeholder = [
			1 => '手机号',
			2 => '邮箱',
			3 => '手机号或邮箱',
		];
		View::with( 'placeholder', $placeholder[ v( 'site.setting.register.item' ) ] );

		return View::make( $this->template . '/register.html' );
	}

	//登录
	public function login() {
		if ( IS_POST ) {
			\Member::login( Request::post() );
			message( '登录成功', Session::get( 'from', __ROOT__ ), 'success' );
		}
		//微信自动登录
		if ( IS_WEIXIN && v( 'site.wechat.level' ) >= 3 && v( 'site.setting.register.focusreg' ) == 1 ) {
			if ( service( 'member' )->weixinLogin() ) {
				$url = q( 'get.backurl', web_url( 'entry/home', [ 'siteid' => SITEID ] ) );
				go( $url );
			}
		}

		return view( $this->template . '/login.html' );
	}

	//验证码
	public function code() {
		Code::fontSize( 15 )->height( 30 )->make();
	}

	//使用微信openid登录
	public function openidLogin() {
		//微信自动登录
		if ( service( 'member' )->weixinLogin() ) {
			$url = q( 'get.backurl', web_url( 'entry/home', [ 'siteid' => SITEID ] ) );
			go( $url );
		}
		message( '微信登录失败,请检查微信公众号是否验证', 'back', 'error' );
	}

	//退出
	public function out() {
		$url = Session::get( 'from', url( 'entry.login' ) );
		\Session::flush();
		message( '退出成功', $url, 'success', 1 );
	}
}