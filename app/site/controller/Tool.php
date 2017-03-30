<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace app\site\controller;

use houdunwang\session\Session;

/**
 * 工具处理类
 * Class Tool
 * @package app\site\controller
 */
class Tool {
	/**
	 * 发送验证码系统会自动识别手机或邮箱
	 */
	public function sendValidCode() {
		$status = \Msg::sendValidCode( Request::post( 'username' ) );
		if ( $status ) {
			$res = [ 'valid' => 1, 'message' => '验证码已经发送到 ' . Request::post( 'username' ) ];
		} else {
			$res = [ 'valid' => 0, 'message' => \Msg::getError() ];
		}
		ajax( $res );
	}
}