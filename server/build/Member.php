<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace server\build;
/**
 * 会员接口
 * Class Member
 * @package server
 * @author 向军
 */
class Member {
	//检测用户登录
	public function isLogin() {
		if ( ! Session::get( "member.uid" ) ) {
			message( '请登录后操作', web_url( 'reg/login', [ ], 'uc' ), 'error' );
		}

		return TRUE;
	}

	//更改会员SESSION数据
	public function updateSession() {
		return m( 'Member' )->updateUserSessionData();
	}

}