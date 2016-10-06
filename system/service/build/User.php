<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\service\build;

/**
 * 用户管理服务
 * Class User
 * @package system\service\build
 */
class User {
	/**
	 * 超级管理员检测
	 *
	 * @param int $uid
	 *
	 * @return bool
	 */
	function isSuperUser( $uid = 0 ) {
		$uid  = $uid ?: v( "user.info.uid" );
		$user = Db::table( 'user' )->find( $uid );

		return $user && $user['groupid'] == 0;
	}

	/**
	 * 是否为站长
	 * 当前帐号是否拥有站长权限(网站所有者,系统管理员)
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool
	 */
	public function isOwner( $siteId = 0, $uid = 0 ) {
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->where( 'role', 'owner' )->get() ? TRUE : FALSE;
	}

	/**
	 * 是否拥有管理员权限
	 * 是否拥有管理员及以上权限(网站所有者,系统管理员)
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function isManage( $siteId = NULL, $uid = NULL ) {
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->WhereIn( 'role', [ 'manage', 'owner' ] )->get();
	}


	/**
	 * 是否拥有操作员权限
	 * 是否拥有操作员及以上权限(网站所有者,系统管理员,操作员)
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function isOperate( $siteId = 0, $uid = 0 ) {
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid, 'return' ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->WhereIn( 'role', [ 'owner', 'manage', 'operate' ] )->get();
	}

	/**
	 * 用户登录
	 *
	 * @param array $data 登录数据
	 *
	 * @return bool
	 */
	public function login( array $data ) {
		$user = Db::table( 'user' )->where( 'username', $data['username'] )->first();
		if ( ! $this->checkPassword( $data['password'], $user['username'] ) ) {
			message( '密码输入错误', 'back', 'error' );
		}

		if ( ! $user['status'] ) {
			message( '您的帐号正在审核中', 'back', 'error' );
		}
		//更新登录状态
		$data             = [ ];
		$data['lastip']   = Request::ip();
		$data['lasttime'] = time();
		Db::table( 'user' )->where( 'uid', $user['uid'] )->update( $data );
		Session::set( "admin_uid", $user['uid'] );

		return TRUE;
	}

	/**
	 * 验证密码是否正确
	 *
	 * @param string $password 登录密码
	 * @param $username 用户名
	 *
	 * @return bool
	 */
	public function checkPassword( $password, $username ) {
		$user = Db::table( 'user' )->where( 'username', $username )->first();

		return $user && $user['password'] == md5( $password . $user['security'] );
	}

	/**
	 * 登录验证
	 * 没有登录异步请求会返回json数据否则直接跳转到登录页
	 * @return bool
	 */
	public function loginAuth() {
		if ( Session::get( 'admin_uid' ) ) {
			return TRUE;
		}
		message( '请登录后进行操作', u( 'system/entry/login' ), 'error' );
	}

	/**
	 * 更新管理员SESSION数据
	 */
	public function updateUserSessionData() {
		$user = Db::table( 'user' )->where( 'uid', Session::get( 'user.uid' ) )->find();
		Session::set( 'user', $user );
	}

	/**
	 * 根据标识验证模块的访问权限
	 *
	 * @param string $identify 权限标识
	 * @param string $type system 系统模块 / 插件模块的名称
	 *
	 * @return bool
	 */
	public function auth( $identify, $type ) {
		$permission = Db::table( 'user_permission' )->where( 'siteid', SITEID )->where( 'uid', Session::get( 'user.uid' ) )->get();
		if ( empty( $permission ) ) {
			return TRUE;
		}
		foreach ( $permission as $v ) {
			if ( $v['type'] == $type && in_array( $identify, explode( '|', $v['permission'] ) ) ) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * 根据权限标识验证访问权限
	 *
	 * @param string $identify 验证标识
	 * @param string $module 类型 system系统模块或模块英文标识
	 *
	 * @return bool
	 */
	public function verify( $identify, $module = NULL ) {
		$module     = $module ?: 'system';
		$permission = Db::table( 'user_permission' )
		                ->where( 'uid', Session::get( 'user.uid' ) )
		                ->where( 'siteid', SITEID )
		                ->where( 'type', $module )
		                ->pluck( 'permission' );
		if ( empty( $permission ) || in_array( $identify, explode( '|', $permission ) ) ) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * 获取站长信息
	 *
	 * @param $siteId 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteOwner( $siteId ) {
		$uid = Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'role', 'owner' )->pluck( 'uid' );
		if ( $uid ) {
			return Db::table( 'user' )->find( $uid );
		}
	}


	/**
	 * 根据用户SESSION数据获取用户所在站点角色
	 * 系统管理员和网站所有者返回owner
	 * @return bool
	 */
	public function role() {
		$siteid = Session::get( 'siteid' );
		$uid    = Session::get( 'user.uid' );
		if ( empty( $siteid ) || empty( $uid ) ) {
			return FALSE;
		}
		//系统管理员拥有同站长一样的角色
		if ( Db::table( 'user' )->where( 'uid', $uid )->pluck( 'groupid' ) == 0 ) {
			return 'owner';
		} else {
			$sql = "SELECT su.role FROM " . tablename( 'user' ) . " u " . "JOIN " . tablename( 'site_user' ) . " su ON u.uid=su.uid " . "WHERE u.uid={$uid} AND su.siteid={$siteid}";
			$res = Db::select( $sql );

			return $res ? $res[0]['role'] : FALSE;
		}
	}

	/**
	 * 获取用户拥有的站点数量
	 *
	 * @param $uid
	 *
	 * @return mixed
	 */
	public function siteNums( $uid ) {
		return Db::table( 'site_user' )->where( 'uid', $uid )->where( 'role', 'owner' )->count();
	}

	/**
	 * 获取用户在站点角色中文描述
	 *
	 * @param $siteid
	 * @param $uid
	 *
	 * @return string
	 */
	public function getRoleTitle( $siteid, $uid ) {
		if ( ( new User() )->isSuperUser() ) {
			return '系统管理员';
		}
		$role = $this->where( 'siteid', $siteid )->where( 'uid', $uid )->pluck( 'role' );
		$data = [ 'owner' => '所有者', 'manage' => '管理员', 'operate' => '操作员' ];

		return $role ? $data[ $role ] : '';
	}

	/**
	 * 设置站点的站长
	 *
	 * @param int $siteid 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return int 自增主键
	 */
	public function setSiteOwner( $siteid, $uid ) {
		//系统管理员不添加数据
		if ( ( new User() )->isSuperUser( $uid ) ) {
			return TRUE;
		}
		$data = [
			'siteid' => $siteid,
			'role'   => 'owner',
			'uid'    => $uid
		];

		return $this->add( $data );
	}

	/**
	 * 获取默认组
	 */
	public function getDefaultGroup() {
		return 1;
	}

	/**
	 * 获取用户的用户组信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserGroup( $uid ) {
		$group_id = Db::table( 'user' )->where( 'uid', $uid )->pluck( 'groupid' );

		return $this->find( $group_id );
	}

	/**
	 * 获取用户在站点的权限分配
	 *
	 * @param int $siteid 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserAtSiteAccess( $siteid = NULL, $uid = NULL ) {
		$siteid     = $siteid ?: Session::get( 'siteid' );
		$uid        = $uid ?: Session::get( "user.uid" );
		$permission = $this->where( 'siteid', $siteid )->where( 'uid', $uid )->lists( 'type,permission' );
		foreach ( $permission as $m => $p ) {
			$permission[ $m ] = explode( '|', $p );
		}

		return $permission;
	}
}