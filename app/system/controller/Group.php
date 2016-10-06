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
use system\model\UserGroup;

/**
 * 用户组管理
 * Class Group
 * @package core\controller
 * @author 向军
 */
class Group {
	public function __construct() {
		//验证超级管理员权限
		service( 'user' )->superUserAuth();
	}

	//用户组列表
	public function lists() {
		$groups = Db::table( 'user_group' )->get();

		return view()->with( 'groups', $groups );
	}

	//删除
	public function remove() {
		$userGroup = new UserGroup();
		foreach ( (array) Request::post( 'id' ) as $id ) {
			$userGroup->delete( $id );
			//更改默认用户组
			Db::table( 'user' )->where( 'groupid', $id )->update( [ 'groupid' => v( 'system.register.groupid' ) ] );
		}
		message( '更新用户组成功', 'lists', 'success' );
	}

	//编辑用户组
	public function post() {
		$group = new UserGroup();
		if ( IS_POST ) {
			$group->id       = Request::post( 'id', 0 );
			$group->name     = Request::post( 'name' );
			$group->maxsite  = Request::post( 'maxsite', 1, 'intval' );
			$group->daylimit = Request::post( 'daylimit', 7, 'intval' );
			$group->package  = Request::post( 'package', [ ] );
			$group->save();
			message( '用户组数据保存成功', 'lists', 'success' );
		}
		if ( $group = $group->find( Request::get( 'id' ) ) ) {
			$group['package'] = unserialize( $group['package'] ) ?: [ ];
		}
		//系统所有套餐
		$packages = service( 'package' )->getSystemAllPackageData();

		return view()->with( [ 'packages' => $packages, 'group' => $group ] );
	}
}