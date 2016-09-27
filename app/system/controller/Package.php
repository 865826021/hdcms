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
 * 套餐
 * Class Package
 * @package core\controller
 * @author 向军
 */
class Package {
	protected $user;
	protected $package;

	public function __construct() {
		$this->package = new \system\model\Package();
		$this->user    = new User();
		if ( ! $this->user->isSuperUser() ) {
			message( '只有系统管理员可以执行套餐管理', 'back', 'error' );
		}
	}

	//套餐列表
	public function lists() {
		$packages = Db::table( 'package' )->get();
		foreach ( $packages as $k => $v ) {
			//套餐模块
			$modules                   = unserialize( $v['modules'] ) ?: [ ];
			$packages[ $k ]['modules'] = $modules ? Db::table( 'modules' )->whereIn( 'name', $modules )->lists( 'title' ) : [ ];
			//套餐模板
			$templates                  = unserialize( $v['template'] ) ?: [ ];
			$packages[ $k ]['template'] = $templates ? Db::table( 'template' )->whereIn( 'name', $templates )->lists( 'title' ) : [ ];
		}
		View::with( 'data', $packages )->make();
	}

	//编辑&添加套餐
	public function post() {
		if ( IS_POST ) {
			$action = q( 'post.id' ) ? 'save' : 'add';
			if ( ! $this->package->$action() ) {
				message( $this->package->getError(), 'back', 'error' );
			}
			message( '套餐更新成功', 'system/package/lists', 'success' );
		}
		$field             = Db::table( 'package' )->where( 'id', q( 'get.id' ) )->first();
		$field['modules']  = unserialize( $field['modules'] ) ?: [ ];
		$field['template'] = unserialize( $field['template'] ) ?: [ ];
		$modules           = Db::table( 'modules' )->orderBy('is_system','DESC')->get();
		$templates           = Db::table( 'template' )->get();
		View::with( 'modules', $modules );
		View::with( 'templates', $templates );
		View::with( 'field', $field );
		View::make();
	}

	//删除套餐
	public function remove() {
		foreach ( (array) $_POST['id'] as $id ) {
			$this->package->delete( $id );
		}
		message( '删除套餐成功', 'back', 'success' );
	}
}