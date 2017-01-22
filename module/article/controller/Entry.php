<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace module\article\controller;

use module\HdController;

/**
 * 前台入口处理
 * Class Entry
 * @package module\article\controller
 */
class Entry extends HdController {
	public function home() {
		view( $this->template . '/home.html' );
	}

	/**
	 * 栏目访问入口
	 */
	public function category() {

	}

	/**
	 * 文章内容页访问入口
	 */
	public function content() {

	}
}