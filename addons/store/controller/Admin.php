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

use module\HdController;

/**
 * 后台主页
 * Class Admin
 * @package addons\store\controller
 */
class Admin extends HdController {
	public function __construct() {
		parent::__construct();
		\Member::isLogin();
	}

	public function home() {
		return view( $this->template . '/admin.home.html' );
	}
}