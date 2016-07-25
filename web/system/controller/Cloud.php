<?php namespace web\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
use system\model\Package;
use system\model\User;
use system\model\UserGroup;

/**
 * 云帐号管理
 * Class Cloud
 * @package web\system\controller
 * @author 向军
 */
class Cloud {
	protected $user;

	public function __construct() {
		$this->user = new User();
		if ( ! $this->user->isSuperUser() ) {
			message( '只有系统管理员可以执行操作', 'back', 'error' ); 
		}
	}

	/**
	 * 云帐号管理
	 */
	public function account() {
		if ( IS_POST ) {
			$res = \Cloud::checkConnect( $_POST['AppID'], $_POST['AppSecret'] );
			if ( $res['valid'] == 1 ) {
				//连接成功
				$data['AppID']     = $_POST['AppID'];
				$data['AppSecret'] = $_POST['AppSecret'];
				Db::table( 'cloud_user' )->where( 'id', 1 )->replace( $data );
				message( '连接成功', 'refresh', 'success' );
			}
			message( '云服务连接失败', 'back', 'error' );
		}
		$field = Db::table( 'cloud_user' )->find( 1 );
		View::with( 'field', $field );
		View::make();
	}
}