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
		\User::superUserAuth();
	}

	//用户组列表
	public function lists() {
		$groups = Db::table( 'user_group' )->get();

		return view()->with( 'groups', $groups );
	}

	//删除
	public function remove() {
		foreach ( (array) Request::post( 'id' ) as $id ) {
			UserGroup::where( 'system_group', 0 )->delete( $id );
			//更改用户组下的用户组为系统默认用户组
			Db::table( 'user' )->where( 'groupid', $id )
			  ->update( [ 'groupid' => v( 'config.register.groupid' ) ] );
		}
		message( '更新用户组成功', 'lists' );
	}

	//编辑用户组
	public function post() {
		//组编号
		$id = Request::get( 'id', 0 );
		if ( IS_POST ) {
			$model                   = $id ? UserGroup::find( $id ) : new UserGroup();
			$model['name']           = Request::post( 'name' );
			$model['maxsite']        = Request::post( 'maxsite', 1, 'intval' );
			$model['daylimit']       = Request::post( 'daylimit', 7, 'intval' );
			$model['middleware_num'] = Request::post( 'middleware_num', 100, 'intval' );
			$model['router_num']     = Request::post( 'router_num', 100, 'intval' );
			$model['package']        = Request::post( 'package', [ ] );
			$model->save();
			message( '用户组数据保存成功', 'lists', 'success' );
		}
		//系统所有套餐
		$packages = \Package::getSystemAllPackageData();
		//获取当前级资料包括为组定义的独立套餐
		if ( $group = UserGroup::find( $id ) ) {
			$group            = $group->toArray();
			$group['package'] = json_decode( $group['package'], true ) ?: [ ];
		}
		return view()->with( [ 'packages' => $packages, 'group' => $group ] );
	}
}