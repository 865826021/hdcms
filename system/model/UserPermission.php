<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\model;

use hdphp\model\Model;

//用户站点操作权限
class UserPermission extends Model {
	protected $table = 'user_permission';
	protected $validate
	                 = [
			[ 'uid', 'required', '用户编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'siteid', 'required', '站点编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'type', 'required', '模块类型不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'permission', 'required', '权限内容不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                 = [
			[ 'permission', 'autoPermission', 'method', self::MUST_AUTO, self::MODEL_BOTH ]
		];

	protected function autoPermission( $val ) {
		return implode( '|', $val );
	}

	/**
	 * 是否为站长
	 * 当前帐号是否拥有站长权限(网站所有者,系统管理员)
	 *
	 * @param $siteid 站点编号
	 * @param $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool
	 */
	public function isOwner( $siteid, $uid = NULL ) {
		$uid = $uid ?: Session::get( "user.uid" );
		if ( m( 'User' )->isSuperUser( $uid ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteid )->where( 'uid', $uid )->where( 'role', 'owner' )->get() ? TRUE : FALSE;
	}

	/**
	 * 是否为管理员
	 * 是否拥有管理员及以上权限(网站所有者,系统管理员)
	 *
	 * @param $siteid 站点编号
	 * @param $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function isManage( $siteid, $uid = NULL ) {
		$uid = $uid ?: Session::get( "user.uid" );
		if ( m( 'User' )->isSuperUser( $uid ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )
		         ->where( 'siteid', $siteid )
		         ->where( 'uid', $uid )
		         ->WhereIn( 'role', [ 'manage', 'owner' ] )
		         ->get() ? TRUE : FALSE;
	}

	/**
	 * 是否为操作员
	 * 是否拥有操作员及以上权限(网站所有者,系统管理员,操作员)
	 *
	 * @param $siteid 站点编号
	 * @param $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function idOperate( $siteid, $uid = NULL ) {
		$uid = $uid ?: Session::get( "user.uid" );
		if ( m( 'User' )->isSuperUser( $uid ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )
		         ->where( 'siteid', $siteid )
		         ->where( 'uid', $uid )
		         ->WhereIn( 'role', [ 'manage', 'owner' ] )
		         ->get() ? TRUE : FALSE;
	}

	/**
	 * 根据权限标识验证访问权限
	 *
	 * @param $identify 验证标识
	 * @param $type 类型 system系统模块或模块英文标识
	 * @param $siteid 站点编号
	 * @param $uid 用户编号
	 *
	 * @return bool
	 */
	public function authByIdentify( $identify, $type = NULL, $siteid = NULL, $uid = NULL ) {
		$siteid = $siteid ?: v( 'site.siteid' );
		$uid    = $uid ?: Session::get( 'user.uid' );
		$type   = $type ?: v( 'module.name' );
		if ( empty( $siteid ) || empty( $uid ) || empty( $type ) ) {
			$this->error = '站点不存在或用户不存在或模块不存在';

			return FALSE;
		}
		//扩展模块时检测站点能不能用这个模块
		if ( $type != 'system' && ! m( 'Modules' )->hasModule( $siteid, $type ) ) {
			return FALSE;
		}
		$permission = Db::table( 'user_permission' )
		                ->where( 'uid', $uid )
		                ->where( 'siteid', '=', $siteid )
		                ->where( 'type', $type )
		                ->pluck( 'permission' );
		if ( empty( $permission ) || in_array( $identify, explode( '|', $permission ) ) ) {
			return TRUE;
		}

		return FALSE;
	}

	public function getUserAtSiteAccess( $siteid, $uid ) {
		$permission = $this->where( 'siteid', $siteid )->where( 'uid', $_GET['fromuid'] )->lists( 'type,permission' );
		foreach ( $permission as $m => $p ) {
			$permission[ $m ] = explode( '|', $p );
		}

		return $permission;
	}

}