<?php namespace module\ucenter\controller;

use module\HdController;

/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

/**
 * 移动端界面管理
 * Class Mobile
 * @package module\ucenter\controller
 */
class Mobile extends HdController {
	/**
	 * 移动端界面设置
	 */
	public function post() {
		return view( $this->template . '/mobile_post.html' );
	}
}