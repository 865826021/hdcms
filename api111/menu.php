<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace api;

class menu {
	/**
	 * 获取系统菜单
	 */
	public function getSystemMenus() {
		$menus      = Db::table( 'core_menu' )->orderby( 'orderby', 'asc' )->get();
		$menus      = Data::channelLevel( $menus, 0, '', 'id', 'pid' );
		$permission = Db::table( 'user_permission' )
		                ->where( 'uid', Session::get( 'user.uid' ) )
		                ->andWhere( 'type', 'system' )
		                ->andWhere( 'siteid', Session::get( 'siteid' ) )
		                ->pluck( 'permission' );
		$permission = explode( '|', $permission );
		//为用户定义了菜单权限时,只保存允许的菜单
		if ( ! empty( $permission ) ) {
			$tmp = $menus;
			foreach ( $tmp as $k => $v ) {
				foreach ( $v['_data'] as $n => $m ) {
					foreach ( $m['_data'] as $a => $b ) {
						//删除没有权限的菜单
						if ( ! in_array( $b['permission'], $permission ) ) {
							unset( $menus[ $k ][ $n ][ $a ] );
						}
					}
				}
			}
			//如果没有三级菜单项时删除二级菜单
			$tmp = $menus;
			foreach ( $tmp as $k => $v ) {
				foreach ( $v['_data'] as $n => $m ) {
					if ( empty( $m['_data'] ) ) {
						unset( $menus[ $k ]['_data'][ $n ] );
					}
				}
			}
			//如果没有二级菜单项时删除一级菜单
			$tmp = $menus;
			foreach ( $tmp as $k => $v ) {
				if ( empty( $v['_data'] ) ) {
					unset( $menus[ $k ] );
				}
			}
		}

		return $menus;
	}

	//分配菜单
	public function assignMenus() {
		$systemMenus = $this->getSystemMenus();
		$topMenuId   = q( 'get.menuid' ) ?: Db::table( 'core_menu' )->where( 'url', '?s=' . q( 'get.s' ) )->andWhere( 'pid', 0 )->pluck( 'id' );
		//点击的不是顶级菜单时根据点击模块获取顶级菜单编号
		if ( empty( $topMenuId ) ) {
			$id        = Db::table( 'core_menu' )->where( 'url', '?' . $_SERVER['QUERY_STRING'] )->pluck( 'pid' );
			$topMenuId = Db::table( 'core_menu' )->where( 'id', $id )->pluck( 'pid' );
		}
		View::with( 'menus', [
			'topMenuId'   => $topMenuId,
			'systemMenus' => $systemMenus
		] );
	}
}