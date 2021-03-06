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
use houdunwang\session\Session;
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
			if ( ! \Msg::checkValidCode( Request::post( 'code' ) ) ) {
				ajax( [ 'valid' => 0, 'message' => '验证码错误' ] );
			}
			$model    = Member::find( v( 'member.info.uid' ) );
			$newEmail = Request::post( 'email' );
			//如果更改了邮箱，检测邮箱是不是已经被别的用户使用
			if ( $model['email'] != $newEmail ) {
				if ( Member::where( 'email', $newEmail )->where( 'siteid', SITEID )->get() ) {
					ajax( [ 'valid' => 0, 'message' => "{$newEmail} 邮箱已经被其他帐号使用" ] );
				}
			}
			$model['email_valid'] = 1;
			$model['email']       = $newEmail;
			if ( $model->save() ) {
				ajax( [ 'valid' => 1, 'message' => '邮箱绑定成功' ] );
			}
		}
		View::with( 'user', v( 'member.info' ) );
		View::with( 'validTime', \Msg::validCodeTime() );

		return View::make( $this->template . '/my/mail.html' );
	}

	//绑定手机
	public function mobile() {
		if ( IS_POST ) {
			if ( ! \Msg::checkValidCode( Request::post( 'code' ) ) ) {
				ajax( [ 'valid' => 0, 'message' => '验证码错误' ] );
			}
			$model     = Member::find( v( 'member.info.uid' ) );
			$newMobile = Request::post( 'mobile' );
			//如果更改了手机，检测手机号是不是已经被别的用户使用
			if ( $model['mobile'] != $newMobile ) {
				if ( Member::where( 'mobile', $newMobile )->where( 'siteid', SITEID )->get() ) {
					ajax( [ 'valid' => 0, 'message' => "{$newMobile} 手机号已经被其他帐号使用" ] );
				}
			}
			$model['mobile_valid'] = 1;
			$model['mobile']       = $newMobile;
			if ( $model->save() ) {
				ajax( [ 'valid' => 1, 'message' => '手机号绑定成功' ] );
			}
		}
		View::with( 'user', v( 'member.info' ) );
		View::with( 'validTime', \Msg::validCodeTime() );
		return View::make( $this->template . '/my/mobile.html' );
	}
}