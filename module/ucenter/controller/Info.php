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

use system\model\Member;

class Info extends Auth {
	//修改会员信息
	public function setting() {
		if ( IS_POST ) {
			$model = ( new Member() )->find( v( 'member.info.uid' ) );
			if ( $model->save( Request::post() ) ) {
				message( '修改成功', url( 'member/index' ), 'success' );
			}
		}
		View::with( 'user', v( 'member.info' ) );

		return View::make( $this->template . '/change_user_info.html' );
	}
}