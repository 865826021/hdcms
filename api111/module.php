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
class module {
	/**
	 * 站点是否可以使用该模块
	 *
	 * @param $module_name 模块名称
	 *
	 * @return bool
	 */
	public function siteHasExeModule( $module_name ) {
		$modules = v( 'modules' );
		foreach ( $modules as $m ) {
			//站点拥有这个模块
			if ( $m['name'] == $module_name ) {
				return TRUE;
			}
		}
		message( '没有操作该模块的权限', 'back', 'error' );
	}

	/**
	 * 根据权限标识验证模块动作
	 *
	 * @param $identify 标识
	 *
	 * @return bool
	 */
	public function auth( $identify ) {
		$module_name = q( 'get.module', '', 'htmlentities' );
		if ( empty( $module_name ) ) {
			message( '模块不存在,缺少GET参数m ', 'back', 'error' );
		}
		//检测站点是否拥有些模块
		$res = $this->siteHasExeModule( $module_name );
		if ( $res == TRUE ) {
			$permission = Db::table( 'user_permission' )
			                ->where( 'uid', Session::get( 'uid' ) )
			                ->where( 'siteid', v( "site.siteid" ) )
			                ->where( 'type', $module_name )
			                ->pluck( 'permission' );
			//没有单独为用户设置权限时,可以使用
			if ( empty( $permission ) ) {
				return TRUE;
			}
			if ( in_array( $identify, explode( '|', $permission ) ) ) {
				return TRUE;
			}
		}
		message( '没有模块动作的操作权限', 'back', 'error' );
	}

}