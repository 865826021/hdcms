<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace module\ucenter\controller;

use houdunwang\request\Request;
use system\model\Member;

class My extends Auth {
	//修改会员信息
	public function info() {
		if ( IS_POST ) {
			$model = Member::find( v( 'member.info.uid' ) );
			if ( $model->save( Request::post() ) ) {
				message( '修改成功', 'back', 'success' );
			}
		}
		View::with( 'user', v( 'member.info' ) );

		return View::make( $this->template . '/my/info.html' );
	}

	//绑定邮箱
	public function mail() {
		if ( IS_POST ) {
			$model = Member::find( v( 'member.info.uid' ) );
			if ( $model->save( Request::post() ) ) {
				message( '修改成功', 'back', 'success' );
			}
		}
		View::with( 'user', v( 'member.info' ) );

		return View::make( $this->template . '/my/mail.html' );
	}

	//发送验证邮件
	public function sendMail() {
		p(Request::input());
	}
}