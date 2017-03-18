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

use houdunwang\wechat\WeChat;
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
			message( '注册成功,系统将跳转到登录页' );
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
			$url = Session::get( 'from', url( 'member.index', '', 'ucenter' ) );
			Session::del( 'from' );
			ajax( [ 'valid' => 1, 'url' => $url ] );
		}
		//微信自动登录
		if ( IS_WEIXIN && v( 'site.wechat.level' ) >= 3 && v( 'site.setting.register.focusreg' ) == 1 ) {
			\Member::weChatLogin();
		}

		return view( $this->template . '/login.html' );
	}

	//验证码
	public function code() {
		Code::fontSize( 15 )->height( 30 )->make();
	}

	/**
	 * 微信客户端登录
	 * 使用微信openid登录
	 */
	public function weChatLogin() {
		//微信自动登录
		\Member::weChatLogin();
	}

	//退出
	public function out() {
		$url = Session::get( 'from', url( 'entry.login', '', 'ucenter' ) );
		Session::del( 'from' );
		\Session::flush();
		go( $url );
	}

	//微信扫码登录
	public function qrLogin() {
		\Member::qrLogin();
	}
}