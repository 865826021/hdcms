<?php namespace app\system\controller;
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\User;

/**
 * 系统配置管理
 * Class Config
 * @package system\controller
 */
class Config {
	protected $db;

	public function __construct() {
		if ( ! ( new User() )->isSuperUser() ) {
			message( '只有系统管理员可以操作', 'back', 'error' );
		}
		$this->db = new \system\model\Config();
	}

	//注册配置管理
	public function register() {
		if ( IS_POST ) {
			$this->db->save();
			message( '保存成功', 'back', 'success' );
		}
		View::with( 'group', Arr::string_to_int( Db::table( 'user_group' )->get() ) );
		View::with( 'field', Arr::string_to_int( $this->db->getByName( 'register' ) ) );
		View::make();
	}

	//站点开/关设置
	public function site() {
		if ( IS_POST ) {
			$this->db->save();
			message( '保存成功', 'back', 'success' );
		}
		View::with( 'field', Arr::string_to_int( $this->db->getByName( 'site' ) ) );
		View::make();
	}
}