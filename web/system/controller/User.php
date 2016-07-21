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
use system\model\Site;
use system\model\UserGroup;
use web\auth;

/**
 * 用户管理
 * Class User
 * @package core\controller
 * @author 向军
 */
class User {
	protected $db;

	public function __construct() {
		$this->db = new \system\model\User();
		if ( ! $this->db->isLogin() ) {
			message( '请登录后进行操作', 'back', 'error' );
		}
	}

	//用户列表
	public function lists() {
		if ( ! $this->db->isSuperUser() ) {
			message( '只有系统管理员可以进行操作', 'back', 'error' );
		}
		$count = Db::table( 'user' )->leftJoin( 'user_group', 'user.groupid', '=', 'user_group.id' )->where( 'groupid', '<>', '0' )->count();
		$page  = Page::make( $count );
		$users = Db::table( 'user' )
		           ->leftJoin( 'user_group', 'user.groupid', '=', 'user_group.id' )
		           ->where( 'groupid', '<>', '0' )
		           ->limit( Page::limit() )
		           ->get();
		View::with( 'users', $users )->with( 'page', $page );
		View::make();
	}

	//添加用户
	public function add() {
		if ( ! $this->db->isSuperUser() ) {
			message( '只有系统管理员可以进行操作', 'back', 'error' );
		}
		if ( IS_POST ) {
			//用户组过期时间
			$daylimit         = Db::table( 'user_group' )->where( 'id', $_POST['groupid'] )->pluck( 'daylimit' );
			$_POST['groupid'] = intval( $_POST['groupid'] );
			$_POST['regtime'] = time();
			$_POST['endtime'] = $daylimit ? time() + $daylimit * 3600 * 24 : 0;
			if ( $this->db->add() ) {
				message( '添加新用户成功', 'lists', 'success' );
			}
			message( $this->db->getError(), 'back', 'error' );

		}
		$groups = Db::table( 'user_group' )->get();
		View::with( 'groups', $groups );
		View::make();
	}

	//编辑
	public function edit() {
		if ( ! $this->db->isSuperUser() ) {
			message( '只有系统管理员可以进行操作', 'back', 'error' );
		}

		if ( IS_POST ) {
			if ( ! $this->db->save( $_POST ) ) {
				message( $this->db->getError(), 'back', 'error' );
			}
			message( '编辑新用户成功', 'refresh' );
		}
		$profile = Db::table( 'user_profile' )->where( 'uid', $_GET['uid'] )->first();
		//会员数据
		$user = Db::table( 'user' )->where( 'uid', $_GET['uid'] )->first();
		//会员组
		$groups = Db::table( 'user_group' )->get();
		View::with( [
			'groups'  => $groups,
			'user'    => $user,
			'profile' => $profile
		] )->make();
	}

	//锁定或解锁用户
	public function updateStatus() {
		if ( ! $this->db->isSuperUser() ) {
			message( '只有系统管理员可以进行操作', 'back', 'error' );
		}
		Db::table( 'user' )->where( 'uid', $_GET['uid'] )->update( [ 'status' => $_GET['status'] ] );
		message( '操作成功', 'back', 'success' );
	}

	//删除用户
	public function remove() {
		if ( ! $this->db->isSuperUser() ) {
			message( '只有系统管理员可以删除用户', 'back', 'error' );
		}
		$this->db->remove( $_POST['uid'] );
		message( '删除用户成功', 'back', 'success' );
	}

	//查看用户权限
	public function permission() {
		$user = $this->db->find( $_GET['uid'] );
		//获取用户组信息
		$group = ( new UserGroup() )->getUserGroup( $user['uid'] );
		//获取用户站点信息
		$sites = ( new Site() )->getUserAllSite( $_GET['uid'] );
		//用户套餐
		$packages = ( new Package() )->getUserGroupPackageLists( $user['groupid'] );
		View::with( [
			'group'    => $group,
			'packages' => $packages,
			'sites'    => $sites
		] )->make();
	}

	//修改我的密码
	public function myPassword() {
		if ( IS_POST ) {
			$_POST['uid'] = Session::get( 'user.uid' );
			if ( $this->db->save( $_POST ) ) {
				session_unset();
				session_destroy();
				message( '我的资料修改成功', 'system/entry/login', 'success' );
			}
			message( $this->db->getError(), 'back', 'error' );
		}
		View::make();
	}
}