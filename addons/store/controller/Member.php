<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace addons\store\controller;

/**
 * 开发者会员中心中的用户资料管理
 * Class Member
 * @package addons\store\controller
 */
class Member extends Admin {
	//密钥设置
	public function secret() {
		$user = Db::table( 'store_user' )->where( 'uid', v( 'member.info.uid' ) )->first();
		View::with( 'user', $user );

		return view( $this->template . '/member.secret.html' );
	}

	//更新密钥
	public function updateSecret() {
		$data['secret'] = md5( time() ) . md5( v( 'member.info.uid' ) );
		Db::table( 'store_user' )->where( 'uid', v( 'member.info.uid' ) )->update( $data );
		ajax( [ 'secret' => $data['secret'] ] );
	}
}