<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\controller;

use system\model\Menu;
use system\model\Modules;
use system\model\Site;
use system\model\User;
use system\model\UserPermission;
use web\auth;

/**
 * 站点权限设置
 * Class Permission
 * @package core\controller
 * @author 向军
 */
class Permission {
	protected $db;

	public function __construct() {
		if ( ! m( "User" )->isLogin() ) {
			message( '请登录后进行操作', 'back', 'error' );
		}
		$this->db = new Site();
	}

	//站点管理员设置
	public function users() {
		if ( ! ( new UserPermission() )->isManage( v( 'site.siteid' ) ) ) {
			message( '你没有站点的管理权限', 'back', 'error' );
		}
		//获取除站长外的站点操作员
		$users    = Db::table( 'user' )
		              ->join( 'site_user', 'user.uid', '=', 'site_user.uid' )
		              ->where( 'role', '<>', 'owner' )
		              ->andWhere( 'siteid', v( 'site.siteid' ) )
		              ->get();
		$owner = (new User())->getSiteOwner(v('site.siteid'));
		View::with( [ 'users' => $users, 'owner' => $owner ] )->make();
	}

	//添加操作员
	public function addOperator() {
		$siteid = v( 'site.siteid' );
		if ( ( new UserPermission() )->isManage( $siteid ) ) {
			foreach ( q( 'post.uid', [ ] ) as $uid ) {
				if ( ! Db::table( "site_user" )->where( "uid", $uid )->where( "siteid", $siteid )->get() ) {
					Db::table( 'site_user' )->insert( [
						'siteid' => $siteid,
						'uid'    => $uid,
						'role'   => 'operate'
					] );
				}
			}
			message( '站点操作员添加成功', '', 'success' );
		}
		message( '你没有操作站点的权限', '', 'error' );
	}

	//更改会员的站点角色
	public function changeRole() {
		$siteid = v( 'site.siteid' );
		if ( ( new UserPermission() )->isManage( $siteid ) ) {
			Db::table( 'site_user' )->where( 'uid', $_POST['uid'] )->where( 'siteid', $siteid )->update( [ 'role' => $_POST['role'] ] );
			message( '管理员角色更新成功', '', 'success' );
		} else {
			message( '你没有操作站点的权限', '', 'error' );
		}
	}

	//删除站点用户
	public function removeSiteUser() {
		$siteid = v( 'site.siteid' );
		if ( ( new UserPermission() )->isManage( $siteid ) ) {
			Db::table( 'site_user' )->where( 'siteid', $siteid )->whereIn( 'uid', $_POST['uids'] )->delete();
			message( '站点管理员删除成功', '', 'success' );
		} else {
			message( '你没有操作站点的权限', '', 'error' );
		}
	}

	//设置菜单权限
	public function menu() {
		if ( ! ( new User() )->isSuperUser() ) {
			message( '没有权限进行权限设置' );
		}
		$siteid = v( 'site.siteid' );
		//设置权限的用户
		$uid = q( 'get.fromuid' );
		if ( IS_POST ) {
			$permissionModel = new UserPermission();
			$permissionModel->where( 'siteid', $siteid )->where( 'uid', $uid )->delete();
			//系统权限
			if ( ! empty( $_POST['system'] ) ) {
				$data = [
					'siteid'     => $siteid,
					'uid'        => $_POST['uid'],
					'type'       => 'system',
					'permission' => $_POST['system']
				];
				$permissionModel->add( $data );
			}
			//模块权限
			if ( ! empty( $_POST['modules'] ) ) {
				foreach ( $_POST['modules'] as $module => $actions ) {
					$data = [
						'siteid'     => $siteid,
						'uid'        => $uid,
						'type'       => $module,
						'permission' => $actions
					];
					$permissionModel->add( $data );
				}
			}
			message( '操作人员权限设置成功', 'back', 'success' );
		}
		//读取菜单表
		$menuModel       = new Menu();
		$menus           = $menuModel->getLevelMenuLists();
		$permissionModel = new UserPermission();
		//获取原有权限
		$permission = $permissionModel->getUserAtSiteAccess( $siteid, $_GET['fromuid'] );
		//获取可使用的模块
		$modulesModel = new Modules();
		$modules      = $modulesModel->getSiteAllModules( $siteid );
		//模块权限
		View::with( [
			'menus'      => $menus,
			'modules'    => $modules,
			'permission' => $permission
		] )->make();
	}
}