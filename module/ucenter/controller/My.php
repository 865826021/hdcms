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

use houdunwang\aliyunsms\Sms;
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
			if ( Request::input( 'code' ) != Session::get( 'mailValid' ) ) {
				ajax( [ 'valid' => 0, 'message' => '验证码错误' ] );
			}
			$model    = Member::find( v( 'member.info.uid' ) );
			$newEmail = Request::input( 'email' );
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

		return View::make( $this->template . '/my/mail.html' );
	}

	//发送验证邮件
	public function sendMail() {
		$siteName = v( 'site.info.name' );
		$code     = Tool::rand( 4 );
		Session::set( 'mailValid', $code );
		$body   = "绑定邮箱使用的验证码是 " . $code . "<br/>感谢使用 {$siteName} 服务";
		$status = Mail::send( Request::input( 'email' ), '', $siteName, $body );
		if ( $status ) {
			$res = [ 'valid' => 1, 'message' => '验证码已经发送到 ' . Request::input( 'mail' ) ];
		} else {
			$res = [ 'valid' => 0, 'message' => '验证码发送失败，请稍候再试 ' ];
		}
		ajax( $res );
	}

	//绑定手机
	public function mobile() {
		if ( IS_POST ) {
			if ( Request::input( 'code' ) != Session::get( 'mobileValid' ) ) {
				ajax( [ 'valid' => 0, 'message' => '验证码错误' ] );
			}
			$model    = Member::find( v( 'member.info.uid' ) );
			$newMobile = Request::input( 'mobile' );
			//如果更改了邮箱，检测邮箱是不是已经被别的用户使用
			if ( $model['mobile'] != $newMobile ) {
				if ( Member::where( 'mobile', $newMobile )->where( 'siteid', SITEID )->get() ) {
					ajax( [ 'valid' => 0, 'message' => "{$newMobile} 手机号已经被其他帐号使用" ] );
				}
			}
			$model['email_valid'] = 1;
			$model['mobile']       = $newMobile;
			if ( $model->save() ) {
				ajax( [ 'valid' => 1, 'message' => '手机号绑定成功' ] );
			}
		}
		View::with( 'user', v( 'member.info' ) );

		return View::make( $this->template . '/my/mobile.html' );
	}

	//发送手机验证码
	public function sendMobile() {
		$code   = Tool::rand( 4 );
		$mobile = trim( Request::input( 'mobile' ) );
		if (! preg_match( '/^\d{11}$/', $mobile ) ) {
			ajax( [ 'valid' => 1, 'message' => '手机号格式错误 ' ] );
		}
		Session::set( 'mobileValid', $code );
		$data['mobile']          = $mobile;
		$data['template_code']   = 'SMS_12840367';
		$data['vars']['code']    = $code;
		$data['vars']['product'] = v( 'site.info.name' );
		if ( Sms::send( $data ) ) {
			$res = [ 'valid' => 1, 'message' => '验证码已经发送到 ' . Request::input( 'mobile' ) ];
		} else {
			$res = [ 'valid' => 0, 'message' => '验证码发送失败，请稍候再试<br/>' . Sms::getError() ];
		}
		ajax( $res );
	}
}