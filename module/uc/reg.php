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

class reg extends hdSite {
	protected $member;

	public function __construct() {
		parent::__construct();
		$this->member = new Member();
	}



	//找回密码
	public function doWEbRetrieve() {

	}
}