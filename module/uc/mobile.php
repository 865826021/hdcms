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

class mobile extends hdSite {
	protected $db;

	public function __construct() {
		parent::__construct();
		$this->db = new Member();
	}

	//修改手机号
	public function doWebChangeMobile() {
		if ( IS_POST ) {
			Validate::make( [
				[ 'mobile', 'required|phone', '手机号输入错误', 3 ],
			] );
			if ( Validate::fail() ) {
				message( Validate::getError(), 'back', 'error' );
			}
			if ( $this->db->where( 'mobile', $_POST['mobile'] )->where( 'uid', '<>', Session::get( 'member.uid' ) )->get() ) {
				message( '手机号已经被使用', 'back', 'error' );
			}
			$_POST['uid'] = Session::get( 'member.uid' );
			if ( $d = $this->db->save() ) {
				//更新用户session
				$this->db->updateUserSessionData();
				message( '手机号更新成功', web_url( 'entry/home' ), 'success' );
			}
			message( $this->db->getError(), 'back', 'error' );
		}
		View::make( $this->ucenter_template .'/change_mobile.html' );
	}
}