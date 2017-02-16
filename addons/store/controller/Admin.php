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

use addons\store\model\StoreUser;
use module\HdController;

/**
 * 后台主控制器
 * 用于其他控制器继承使用
 * Class Admin
 * @package addons\store\controller
 */
class Admin extends HdController {
	public function __construct() {
		parent::__construct();
		\Member::isLogin();
		/**
		 * 初始登录的帐号
		 * 为帐号设置密钥
		 */
		$user = Db::table( 'store_user' )->where( 'uid', v( 'member.info.uid' ) )->first();
		if ( empty( $user ) ) {
			$data['secret'] = '';
			$data['uid']    = v( 'member.info.uid' );
			$model = new StoreUser();
			$model->save( $data );
			//设置初始大神积分
			$data['uid']        = v( 'member.info.uid' );
			$data['credittype'] = 'credit1';
			$data['num']        = 100;
			$data['remark']     = '开发者初始大神积分';
			\Credit::change( $data );
		}
	}

	//后台主页
	public function home() {
		return view( $this->template . '/admin/home.html' );
	}
}