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
		service( 'user' )->loginAuth();
	}

	//站点管理员设置
	public function users() {
		$User = new User();
		if ( ! service( 'user' )->isManage() ) {
			message( '你没有站点的管理权限', 'back', 'error' );
		}
		//获取除站长外的站点操作员
		$users = $User->join( 'site_user', 'user.uid', '=', 'site_user.uid' )->where( 'role', '<>', 'owner' )->andWhere( 'siteid', SITEID )->get();
		//站长数据
		$owner = service( 'user' )->getSiteOwner( SITEID );

		return view()->with( [ 'users' => $users, 'owner' => $owner ] );
	}

	//添加操作员
	public function addOperator() {
		if ( service( 'user' )->isManage() ) {
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
		if ( service( 'user' )->isManage() ) {
			Db::table( 'site_user' )->where( 'uid', $_POST['uid'] )->where( 'siteid', SITEID )->update( [ 'role' => $_POST['role'] ] );
			message( '管理员角色更新成功', '', 'success' );
		} else {
			message( '你没有操作站点的权限', '', 'error' );
		}
	}

	//删除站点用户
	public function removeSiteUser() {
		if ( ( new User() )->isManage() ) {
			Db::table( 'site_user' )->where( 'siteid', SITEID )->whereIn( 'uid', $_POST['uids'] )->delete();
			message( '站点管理员删除成功', '', 'success' );
		} else {
			message( '你没有操作站点的权限', '', 'error' );
		}
	}

	//设置菜单权限
	public function menu() {
		service( 'user' )->superUserAuth();
		//设置权限的用户
		$uid = Request::get( 'fromuid' );
		if ( IS_POST ) {
			$permissionModel = new UserPermission();
			$permissionModel->where( 'siteid', SITEID )->where( 'uid', $uid )->delete();
			//如果选择了模块,将扩展模块菜单要选中
			$_POST['system'] = empty( $_POST['system'] ) ? [ ] : $_POST['system'];
			if ( ! empty( $_POST['modules'] ) && ! in_array( 'package_managa', $_POST['system'] ) ) {
				$_POST['system'][] = 'package_managa';
			}
			//系统权限
			if ( ! empty( $_POST['system'] ) ) {
				$permissionModel['siteid']     = SITEID;
				$permissionModel['uid']        = $uid;
				$permissionModel['type']       = 'system';
				$permissionModel['permission'] = implode( '|', $_POST['system'] );
				$permissionModel->save();
			}
			//模块权限
			if ( ! empty( $_POST['modules'] ) ) {
				foreach ( $_POST['modules'] as $module => $actions ) {
					$permissionModel['siteid']     = SITEID;
					$permissionModel['uid']        = $uid;
					$permissionModel['type']       = $module;
					$permissionModel['permission'] = implode( '|', $actions );;
					$permissionModel->save();
				}
			}
			message( '操作人员权限设置成功', 'refresh', 'success' );
		}
		//读取菜单表
		$menus = service( 'menu' )->getLevelMenuLists();
		//获取原有权限
		$permission = service( 'user' )->getUserAtSiteAccess( SITEID, $uid );
		//获取可使用的模块
		$modules = service( 'module' )->getSiteAllModules( SITEID, FALSE );

		//模块权限
		return view()->with( [
			'menus'      => $menus,
			'modules'    => $modules,
			'permission' => $permission
		] );
	}
}