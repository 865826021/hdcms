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
use system\model\Site;
use system\model\UserGroup;

/**
 * 用户管理
 * Class User
 * \ * @package core\controller
 * @author 向军
 */
class User {
	//管理员模型
	protected $user;

	public function __construct() {
		$User = new \system\model\User();
		$User->isLogin();
	}

	//用户列表
	public function lists() {
		$User = new \system\model\User();
		$User->isSuperUser( v( 'user.uid' ) );
		$users = $User->leftJoin( 'user_group', 'user.groupid', '=', 'user_group.id' )->where( 'groupid', '>=', '0' )->paginate( 8 );

		return view()->with( 'users', $users );
	}

	//添加用户
	public function add() {
		$User = new \system\model\User();
		$User->isSuperUser( v( 'user.uid' ) );
		if ( IS_POST ) {
			Validate::make( [
				[ 'password', 'confirm:password2', '两次密码输入不一致' ]
			] );
			//用户组过期时间
			$daylimit         = Db::table( 'user_group' )->where( 'id', $_POST['groupid'] )->pluck( 'daylimit' );
			$User['username'] = Request::post( 'username' );
			$User['remark']   = Request::post( 'remark' );
			$User['endtime']  = time() + $daylimit * 3600 * 24;
			if ( $User->save() ) {
				message( '添加新用户成功', 'lists', 'success' );
			}
			message( $User->getError(), 'back', 'error' );

		}
		$groups = Db::table( 'user_group' )->get();

		return view()->with( 'groups', $groups );
	}

	//编辑
	public function edit() {
		$User = ( new \system\model\User() )->find( Request::get( 'uid' ) );
		$User->isSuperUser( v( 'user.uid' ) );
		if ( IS_POST ) {
			Validate::make( [
				[ 'endtime', 'required', '到期时间不能为空', 3 ],
				[ 'password', 'confirm:password2', '两次密码输入不一致', 3 ]
			] );
			$User->password = Request::post( 'password' );
			$User->endtime  = Request::post( 'endtime' );
			$User->groupid  = Request::post( 'groupid' );
			$User->remark   = Request::post( 'remark' );
			$User->qq       = Request::post( 'qq' );
			$User->mobile   = Request::post( 'mobile' );
			if ( $User->save() ) {
				message( '编辑新用户成功', 'lists' );
			}
			message( $User->getError(), 'back', 'error' );
		}
		//会员组
		$groups = Db::table( 'user_group' )->get();

		return view()->with( [
			'groups' => $groups,
			'user'   => $User
		] );
	}

	//锁定或解锁用户
	public function updateStatus() {
		$User = ( new \system\model\User() )->find( Request::get( 'uid' ) );
		$User->isSuperUser( v( 'user.uid' ) );

		if ( $User->isSuperUser( $User->uid, 'return' ) ) {
			message( '管理员帐号不允许操作', 'back', 'error' );
		}
		$User->status = Request::get( 'status' );
		$User->save();
		message( '操作成功', 'back', 'success' );
	}

	//删除用户
	public function remove() {
		$User = ( new \system\model\User() )->find( Request::post( 'uid' ) );
		$User->isSuperUser( v( 'user.uid' ) );

		if ( $User->isSuperUser( $User->uid, 'return' ) ) {
			message( '管理员帐号不允许删除', 'back', 'error' );
		}
		$User->remove();
		message( '删除用户成功', 'back', 'success' );
	}

	/**
	 * 查看用户权限
	 * @return mixed
	 */
	public function permission() {
		$User    = \system\model\User::find( Request::get( 'uid' ) );
		$Site    = new Site();
		$Package = new Package();
		//获取用户组信息
		$group = $User->userGroup();
		//获取用户站点信息
		$sites = $Site->getUserAllSite( $User['uid'] );
		//用户套餐
		$packages = $Package->getUserGroupPackageLists( $User['groupid'] );
		return view()->with( [
			'group'    => $group,
			'packages' => $packages,
			'sites'    => $sites
		] );
	}

	/**
	 * 修改站长密码
	 * @return mixed
	 */
	public function myPassword() {
		$User = ( new \system\model\User() )->find( v( 'user.uid' ) );
		if ( IS_POST ) {
			Validate::make( [
				[ 'password', 'required', '密码不能为空' ],
				[ 'password', 'confirm:password2', '两次密码输入不一致' ]
			] );
			$User->password = Request::post( 'password' );
			if ( $User->save() ) {
				\Session::flush();
				message( '我的资料修改成功', 'system/entry/login', 'success' );
			}
			message( $User->getError(), 'back', 'error' );
		}

		return view();
	}
}