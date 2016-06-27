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

class mobile extends hdSite {
	//修改手机号
	public function doWebChangeMobile() {
		if ( IS_POST ) {
			Validate::make( [
				[ 'mobile', 'required|phone', '原手机号输入错误', 2 ],
				[ 'new_mobile', 'required|phone', '新手机号输入错误', 2 ],
				[ 'password', 'required', '密码不能为空', 2 ],
			] );
			$user = Db::table( 'member' )->where( 'uid', $_SESSION['member']['uid'] )->first();
			if ( $user['password'] != md5( $_POST['password'] . $user['security'] ) ) {
				message( '密码输入错误', 'back', 'error' );
			}
			$data['mobile'] = $_POST['new_mobile'];
			Db::table( 'member' )->where( 'uid', $_SESSION['user']['uid'] )->update( $data );
			//更新用户session
			m('Member')->updateUserSessionData();
			message( '手机号更新成功', web_url( 'uc/entry/home' ), 'success' );
		}
		View::make( 'ucenter/change_mobile.html' );
	}
}