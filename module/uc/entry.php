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

/**
 * 前台会员中心
 * Class entry
 * @package uc\controller
 */
class entry extends hdSite {
	protected $member;

	public function __construct() {
		parent::__construct();
		$this->member = new Member();
		//没有登录时跳转到登录页面
		if ( ! $this->member->isLogin() ) {
			go( web_url( 'reg/login' ) );
		}
	}

	//显示会员中心页面
	public function doWebHome() {
		//更新用户session
		$this->member->updateUserSessionData();
		$uc = Db::table( 'web_page' )->where( 'siteid', SITEID )->where( 'type', 3 )->first();
		//获取菜单
		$menus = Db::table( 'web_nav' )->where( 'siteid', SITEID )->where( 'entry', 'profile' )->get();
		//会员信息
		$group = $this->member->getGroupName( Session::get( 'member.uid' ) );
		View::with( [ 'uc' => $uc, 'menus' => $menus, 'user' => Session::get( 'member' ), 'group' => $group ] );
		View::make( $this->ucenter_template . '/home.html' );
	}


}