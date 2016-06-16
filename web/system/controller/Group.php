<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\system\controller;

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
		if ( ! $this->user->isSuperUser() ) {
			message( '只有系统管理员可以执行套餐管理', 'back', 'error' );
		}
	}

	//用户组列表
	public function lists() {
		$groups = Db::table( 'user_group' )->get();
		View::with( 'groups', $groups )->make();
	}

	//删除
	public function remove() {
		foreach ( (array) $_POST['id'] as $id ) {
			$this->userGroup->delete($id);
		}
		message( '更新用户组成功', 'lists', 'success' );
	}

	//编辑用户组
	public function post() {
		if ( IS_POST ) {
			$action = q( 'get.id' ) ? 'save' : 'add';
			if ( ! $id = $this->userGroup->$action() ) {
				message( $this->userGroup->getError(), 'back', 'error' );
			}
			message( '用户组数据更新成功', u( 'post', [ 'id' => $id ] ), 'success' );
		}
		$group            = Db::table( 'user_group' )->where( 'id', q( 'get.id' ) )->first();
		$group['package'] = unserialize( $group['package'] ) ?: [ ];
		//服务套餐
		$packages = ( new Package() )->getSystemAllPackageData();
		View::with( [ 'packages' => $packages, 'group' => $group ] )->make();
	}
}