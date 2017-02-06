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
 * 系统核心包管理
 * Class Hdcms
 * @package addons\store\controller
 */
class Hdcms extends Admin {
	public function lists() {
		return view( $this->template . '/hdcms.lists.html' );
	}
}