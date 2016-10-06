<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace app\system\controller;

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
		$this->user = new User();
		$this->user->isSuperUser( v( 'user.uid' ) );
		$this->package = new \system\model\Package();
	}

	//套餐列表
	public function lists() {
		$packages = $this->package->get() ?: [ ];
		foreach ( $packages as $k => $v ) {
			//套餐模块
			$modules                   = unserialize( $v['modules'] ) ?: [ ];
			$packages[ $k ]['modules'] = $modules ? Db::table( 'modules' )->whereIn( 'name', $modules )->lists( 'title' ) : [ ];
			//套餐模板
			$templates                  = unserialize( $v['template'] ) ?: [ ];
			$packages[ $k ]['template'] = $templates ? Db::table( 'template' )->whereIn( 'name', $templates )->lists( 'title' ) : [ ];
		}

		return view()->with( 'data', $packages );
	}

	//编辑&添加套餐
	public function post() {
		if ( IS_POST ) {
			$this->package->id       = Request::post( 'id' );
			$this->package->name     = Request::post( 'name' );
			$this->package->modules  = Request::post( 'modules' );
			$this->package->template = Request::post( 'template' );
			$this->package->save();
			message( '套餐更新成功', 'lists', 'success' );
		}
		//编辑时获取套餐
		if ( $Package = $this->package->find( Request::get( 'id' ) ) ) {
			$Package['modules']  = unserialize( $Package['modules'] ) ?: [ ];
			$Package['template'] = unserialize( $Package['template'] ) ?: [ ];
		}
		$modules   = Db::table( 'modules' )->orderBy( 'is_system', 'DESC' )->get();
		$templates = Db::table( 'template' )->orderBy( 'is_system', 'DESC' )->get();

		return view()->with( [
			'modules'   => $modules,
			'templates' => $templates,
			'package'   => $Package,
		] );
	}

	//删除套餐
	public function remove() {
		foreach ( (array) Request::post( 'id' ) as $id ) {
			$this->package->remove( $id );
		}
		message( '删除套餐成功', 'back', 'success' );
	}
}