<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\ucenter\controller;

use module\HdController;

/**
 * 后台管理公共控制器
 * 验证登录权限
 * Class Auth
 * @package module\ucenter\controller
 */
class Auth extends HdController {
	public function __construct() {
		//登录权限验证
		memberIsLogin();
		parent::__construct();
		$this->template = "ucenter/" . v( 'site.info.ucenter_template' ) . DIRECTORY_SEPARATOR
		                  . ( IS_MOBILE ? 'mobile' : 'web' );
		template_path( $this->template );
		template_url( __ROOT__ . '/' . $this->template );
		//来源页面
		if ( $from = Request::get( 'from' ) ) {
			Session::set( 'from', $from );
		}
	}
}