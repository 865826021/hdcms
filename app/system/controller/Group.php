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

use system\model\Package;
use system\model\User;
use system\model\UserGroup;

/**
 * 用户组管理
 * Class Group
 * @package core\controller
 * @author 向军
 */
class Group {
	protected $user;
	protected $userGroup;

	public function __construct() {
		$this->user      = new User();
		$this->userGroup = new UserGroup();
		$this->user->isSuperUser( v( 'user.uid' ) );
	}

	//用户组列表
	public function lists() {
		$groups = Db::table( 'user_group' )->get();

		return view()->with( 'groups', $groups );
	}

	//删除
	public function remove() {
		foreach ( (array) Request::post( 'id' ) as $id ) {
			$this->userGroup->delete( $id );
			//更改默认用户组
			Db::table( 'user' )->where( 'groupid', $id )->update( [ 'groupid' => v( 'system.register.groupid' ) ] );
		}
		message( '更新用户组成功', 'lists', 'success' );
	}

	//编辑用户组
	public function post() {
		if ( IS_POST ) {
			$this->userGroup->id       = Request::post( 'id', 0 );
			$this->userGroup->name     = Request::post( 'name' );
			$this->userGroup->maxsite  = Request::post( 'maxsite', 1, 'intval' );
			$this->userGroup->daylimit = Request::post( 'daylimit', 7, 'intval' );
			$this->userGroup->package  = Request::post( 'package', [ ] );
			$this->userGroup->save();
			message( '用户组数据保存成功', 'lists', 'success' );
		}
		$group            = $this->userGroup->find( Request::get( 'id' ) );
		$group['package'] = unserialize( $group['package'] ) ?: [ ];
		//系统所有套餐
		$packages = ( new Package() )->getSystemAllPackageData();

		return view()->with( [ 'packages' => $packages, 'group' => $group ] );
	}
}