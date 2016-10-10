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
		//登录检测
		service( 'user' )->loginAuth();
	}

	//用户列表
	public function lists() {
		//管理员验证
		service( 'user' )->superUserAuth();
		$User = new \system\model\User();

		$users = $User->leftJoin( 'user_group', 'user.groupid', '=', 'user_group.id' )->where( 'groupid', '>', '0' )->paginate( 8 );

		return view()->with( 'users', $users );
	}

	//添加用户
	public function add() {
		//管理员验证
		service( 'user' )->superUserAuth();
		if ( IS_POST ) {
			$User = new \system\model\User();
			Validate::make( [
				[ 'password', 'confirm:password2', '两次密码输入不一致' ]
			] );
			$info           = $User->getPasswordAndSecurity();
			$User->password = $info['password'];
			$User->security = $info['security'];
			//用户组过期时间
			$daylimit         = Db::table( 'user_group' )->where( 'id', Request::post( 'groupid' ) )->pluck( 'daylimit' );
			$User['endtime']  = time() + $daylimit * 3600 * 24;
			$User['groupid']  = Request::post( 'groupid' );
			$User['username'] = Request::post( 'username' );
			$User['remark']   = Request::post( 'remark' );
			$User->save();
			message( '添加新用户成功', 'lists', 'success' );
		}
		$groups = Db::table( 'user_group' )->get();

		return view()->with( 'groups', $groups );
	}

	//编辑
	public function edit() {
		//管理员验证
		service( 'user' )->superUserAuth();
		$User = ( new \system\model\User() )->find( Request::get( 'uid' ) );
		if ( IS_POST ) {
			if ( Request::post( 'password' ) ) {
				Validate::make( [
					[ 'password', 'confirm:password2', '两次密码输入不一致' ]
				] );
				//存在密码时设置密码
				$info           = $User->getPasswordAndSecurity();
				$User->password = $info['password'];
				$User->security = $info['security'];
			}
			$User->endtime = strtotime( Request::post( 'endtime' ) );
			$User->groupid = Request::post( 'groupid' );
			$User->remark  = Request::post( 'remark' );
			$User->qq      = Request::post( 'qq' );
			$User->mobile  = Request::post( 'mobile' );
			$User->save();
			message( '编辑新用户成功', 'lists' );
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
		//管理员验证
		service( 'user' )->superUserAuth();
		$User = ( new \system\model\User() )->find( Request::get( 'uid' ) );
		if ( service( 'user' )->isSuperUser( $User->uid ) ) {
			message( '管理员帐号不允许操作', 'back', 'error' );
		}
		//防止修改密码
		unset( $User['password'] );
		$User->status = Request::get( 'status', 0 );
		$User->save();
		message( '操作成功', 'back', 'success' );
	}

	//删除用户
	public function remove() {
		//管理员验证
		service( 'user' )->superUserAuth();
		$User = ( new \system\model\User() )->find( Request::post( 'uid' ) );

		if ( service( 'user' )->isSuperUser( $User->uid ) ) {
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
		$User = service( 'user' )->find( Request::get( 'uid' ) );
		//获取用户组信息
		$group = $User->userGroup();
		//获取用户站点信息
		$sites = service( 'site' )->getUserAllSite( $User['uid'] );
		//用户套餐
		$packages = service( 'package' )->getUserGroupPackageLists( $User['groupid'] );

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
		$User = new \system\model\User();
		if ( IS_POST ) {
			Validate::make( [
				[ 'password', 'required', '密码不能为空' ],
				[ 'password', 'confirm:password2', '两次密码输入不一致' ]
			] );
			$info           = $User->getPasswordAndSecurity();
			$User->password = $info['password'];
			$User->security = $info['security'];
			$User->uid      = v( "user.info.uid" );
			if ( $User->save() ) {
				\Session::flush();
				message( '我的资料修改成功,系统将引导你重新登录', 'system/entry/login', 'success' );
			}
			message( $User->getError(), 'back', 'error' );
		}

		return view();
	}
}