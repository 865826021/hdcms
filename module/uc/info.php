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

class info extends hdSite {
	public function __construct() {
		parent::__construct();
		Util::instance( 'member' )->isLogin();
	}

	//修改会员信息
	public function doWebSetting() {
		if ( IS_POST ) {
			$_POST['uid'] = Session::get( 'member.uid' );
			if ( m( 'Member' )->save() ) {
				Util::instance( 'member' )->updateSession();
				message( '修改成功', web_url( 'entry/home' ), 'success' );
			}
		}
		View::with( 'user', Session::get( 'member' ) );
		View::make( $this->ucenter_template . '/change_user_info.html' );
	}
}