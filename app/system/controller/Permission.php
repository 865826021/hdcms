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

	public function __construct() {
		if ( ! ( new User )->isLogin() ) {
			message( '请登录后进行操作', 'system/entry/login', 'error' );
		}
	}

	//站点管理员设置
	public function users() {
		$User = new User();
		if ( ! $User->isManage( SITEID ) ) {
			message( '你没有站点的管理权限', 'back', 'error' );
		}
		//获取除站长外的站点操作员
		$users = $User->join( 'site_user', 'user.uid', '=', 'site_user.uid' )->where( 'role', '<>', 'owner' )->andWhere( 'siteid', SITEID )->get();
		//站长数据
		$owner = $User->getSiteOwner( SITEID );

		return view()->with( [ 'users' => $users, 'owner' => $owner ] );
	}

	//添加操作员
	public function addOperator() {
		if ( ( new User() )->isManage( SITEID ) ) {
			foreach ( q( 'post.uid', [ ] ) as $uid ) {
				if ( ! Db::table( "site_user" )->where( "uid", $uid )->where( "siteid", SITEID )->get() ) {
					Db::table( 'site_user' )->insert( [
						'siteid' => SITEID,
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
		if ( ( new User() )->isManage( $siteid ) ) {
			Db::table( 'site_user' )->where( 'uid', $_POST['uid'] )->where( 'siteid', $siteid )->update( [ 'role' => $_POST['role'] ] );
			message( '管理员角色更新成功', '', 'success' );
		} else {
			message( '你没有操作站点的权限', '', 'error' );
		}
	}

	//删除站点用户
	public function removeSiteUser() {
		$siteid = v( 'site.siteid' );
		if ( ( new User() )->isManage( $siteid ) ) {
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
			//如果选择了模块,将扩展模块菜单要选中
			$_POST['system'] = empty( $_POST['system'] ) ? [ ] : $_POST['system'];
			if ( ! empty( $_POST['modules'] ) && ! in_array( 'package_managa', $_POST['system'] ) ) {
				$_POST['system'][] = 'package_managa';
			}
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
			message( '操作人员权限设置成功', 'refresh', 'success' );
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