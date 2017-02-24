<?php namespace system\service\menu;
	/** .-------------------------------------------------------------------
	 * |  Software: [HDPHP framework]
	 * |      Site: www.hdphp.com
	 * |-------------------------------------------------------------------
	 * |    Author: 向军 <2300071698@qq.com>
	 * |    WeChat: aihoudun
	 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
	 * '-------------------------------------------------------------------*/

//服务功能类
class Menu {
	/**
	 * 获取菜单组合的父子级多维数组
	 * @return mixed
	 */
	public function getLevelMenuLists() {
		$menu = Db::table( 'menu' )->get() ?: [ ];

		return \Arr::channelLevel( $menu ?: [ ], 0, '', 'id', 'pid' );
	}

	/**
	 * 获取当前用户在站点后台可以使用的系统菜单
	 * @return mixed
	 */
	public function all() {
		/**
		 * 系统管理
		 * 1 移除系统菜单
		 * 2 将没有三级或二级菜单的菜单移除
		 */
		//移除用户没有使用权限的系统菜单
		$permission = Db::table( 'user_permission' )
		                ->where( 'siteid', SITEID )
		                ->where( 'uid', v( 'user.info.uid' ) )
		                ->where( 'type', 'system' )
		                ->pluck( 'permission' );
		$menus      = Db::table( 'menu' )->orderBy( 'id', 'asc' )->get();
		if ( $permission ) {
			$permission = explode( '|', $permission );
			$tmp        = $menus;
			foreach ( $tmp as $k => $m ) {
				if ( $m['permission'] != '' && ! in_array( $m['permission'], $permission ) ) {
					unset( $menus[ $k ] );
				}
			}
		}
		$menus = \Arr::channelLevel( $menus, 0, '', 'id', 'pid' );
		//移除没有三级菜单的一级与二级菜单
		$tmp = $menus;
		foreach ( $tmp as $k => $t ) {
			//二级菜单为空时删除些菜单
			foreach ( $t['_data'] as $n => $d ) {
				if ( empty( $d['_data'] ) ) {
					unset( $menus[ $k ]['_data'][ $n ] );
				}
			}
			//一级菜单没有子菜单时移除
			if ( empty( $menus[ $k ]['_data'] ) ) {
				unset( $menus[ $k ] );
			}
		}

		return $menus;
	}

	/**
	 * 分配菜单数据到模板
	 * @return mixed
	 */
	public function get() {
		static $menus = null;
		if ( is_null( $menus ) ) {
			$menus = [
				//系统菜单数据
				'menus'       => $this->all(),
				//当前模块
				'module'      => \Module::currentUseModule(),
				//用户在站点可以使用的模块列表
				'moduleLists' => \Module::getBySiteUser()
			];
		}

		return $menus;
	}

	/**
	 * 获取快捷导航菜单
	 * @return array|mixed
	 */
	public function getQuickMenu() {
		$data = Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->where( 'uid', v( 'user.info.uid' ) )->pluck( 'data' );

		return $data ? json_decode( $data, true ) : [ ];
	}

	public function getUserMenuAccess( $siteId = '', $uid = '' ) {
		$siteId     = $siteId ?: SITEID;
		$uid        = $uid ?: v( 'user.info.uid' );
		$permission = \User::getUserAtSiteAccess( $siteId, $uid );
		$menus      = Db::table( 'menu' )->get();
		foreach ( $menus as $k => $v ) {
			$menus[ $k ]['status'] = 0;
			if ( empty( $permission ) ) {
				$menus[ $k ]['_status'] = 1;
			} elseif ( isset( $permission['system'] ) && in_array( $v['permission'], $permission['system'] ) ) {
				$menus[ $k ]['_status'] = 1;
			}
		}

		return $menus;
	}
}