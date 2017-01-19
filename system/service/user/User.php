<?php namespace system\service\user;

use system\model\UserGroup;
use system\service\Common;
use system\model\User as UserModel;

/**
 * 用户管理服务
 * Class User
 * @package system\service\User
 */
class User extends Common {

	/**
	 * 系统临时关闭显示提示信息
	 * @return mixed
	 */
	public function checkSystemClose() {
		/**
		 * 站点关闭检测,系统管理员忽略检测
		 */
		if ( ! $this->isSuperUser() && ! v( 'config.site.is_open' ) ) {
			\Session::flush();

			die( view( 'resource/view/site_close.php' ) );
		}
	}

	/**
	 * 超级管理员检测
	 *
	 * @param int $uid
	 *
	 * @return bool
	 */
	public function isSuperUser( $uid = 0 ) {
		$this->loginAuth();
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
		$this->loginAuth();
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid ) ) {
			return true;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->where( 'role', 'owner' )->get() ? true : false;
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
	public function isManage( $siteId = null, $uid = null ) {
		$this->loginAuth();
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid ) ) {
			return true;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->WhereIn( 'role', [
			'manage',
			'owner'
		] )->get();
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
		$this->loginAuth();
		$siteId = $siteId ?: SITEID;
		$uid    = $uid ?: v( "user.info.uid" );

		if ( $this->isSuperUser( $uid, 'return' ) ) {
			return true;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'uid', $uid )->WhereIn( 'role', [
			'owner',
			'manage',
			'operate'
		] )->get();
	}

	/**
	 * 用户登录
	 *
	 * @param array $data 登录数据
	 * @param bool $process 直接处理
	 *
	 * @return bool|array
	 */
	public function login( array $data, $process = true ) {
		$user = Db::table( 'user' )->where( 'username', $data['username'] )->first();
		if ( empty( $user ) ) {
			message( '帐号不存在', 'back', 'error' );
		}
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
		\Session::set( "admin_uid", $user['uid'] );
		$this->initUserInfo();

		return true;
	}

	//初始用户信息
	public function initUserInfo() {
		//前台访问
		if ( Session::get( "admin_uid" ) ) {
			$user                         = [ ];
			$user['info']                 = UserModel::find( \Session::get( 'admin_uid' ) );
			$user['group']                = UserGroup::where( 'id', $user['info']['groupid'] )->first();
			$user['system']['super_user'] = $user['group']['id'] == 0;
			$user['system']['user_type']  = 'admin';
			v( 'user', $user );
		}
	}

	/**
	 * 验证密码是否正确
	 *
	 * @param string $password 登录密码
	 * @param string $username 用户名
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
		if ( v( 'user' ) ) {
			return true;
		}
		message( '请登录后进行操作', u( 'system/entry/login' ), 'error' );
	}

	/**
	 * 超级管理员验证
	 * 异步请求会返回json数据否则直接回调页面
	 *
	 * @param int $uid
	 *
	 * @return bool
	 */
	public function superUserAuth( $uid = 0 ) {
		$uid = $uid ?: v( "user.info.uid" );
		if ( ! $this->isSuperUser( $uid ) ) {
			$url = v( 'user' ) ? 'back' : u( 'system.entry.login' );
			message( '您不是系统管理员,无法进行操作!', $url, 'error' );
		}

		return true;
	}

	/**
	 * 验证当前用户在当前站点
	 * 能否使用当前模块
	 * 具体模块动作需要使用权限标识独立验证
	 * @return bool
	 * @throws \Exception
	 */
	public function hasModule() {
		static $isAuth = null;
		//验证过的进行缓存,减少验证效次数
		if ( ! is_null( $isAuth ) ) {
			return $isAuth;
		}
		//操作员验证
		if ( ! \User::isOperate() ) {
			$isAuth = false;
		} else {
			//系统模块不受限制
			if ( v( "module.is_system" ) == 1 ) {
				$isAuth = true;
			} else {
				//站点是否含有模块
				if ( ! $this->hasModule( SITEID, v( 'module.name' ) ) ) {
					$isAuth = false;
				} else {
					//插件模块
					$allowModules = UserPermission::where( 'siteid', SITEID )
					                              ->where( 'uid', Session::get( 'user.uid' ) )->lists( 'type' );
					if ( ! empty( $allowModules ) ) {
						return $isAuth = in_array( v( 'module.name' ), $allowModules );
					}
					$isAuth = true;
				}
			}
		}

		return $isAuth;
	}

	/**
	 * 根据标识验证模块的访问权限
	 * 系统模块时使用 system标识验证,因为所有系统模块权限是统一管理的
	 * 插件模块是独立设置的,所以针对插件使用插件名标识进行验证
	 *
	 * @param string $identify 权限标识
	 *
	 * @return bool
	 */
	public function auth( $identify ) {
		$this->loginAuth();
		$status = true;
		if ( $this->hasModule() === false ) {
			$status = false;
		} else {
			$type       = v( 'module.name.is_system' ) ? 'system' : v( 'module.name' );
			$permission = Db::table( 'user_permission' )->where( 'siteid', SITEID )->where( 'uid', v( "user.info.uid" ) )->get();
			if ( empty( $permission ) ) {
				$status = true;
			}
			foreach ( $permission as $v ) {
				if ( $v['type'] == $type && in_array( $identify, explode( '|', $v['permission'] ) ) ) {
					$status = true;
				}
			}
		}
		if ( $status === false ) {
			message( '你没有访问权限', '', 'error' );
		}

		return true;
	}

	/**
	 * 模块访问验证
	 * 当前站点不能使用这个模块或用户没有管理权限时
	 * 验证将失败
	 */
	public function moduleVerify() {
		if ( ! \Module::hasModule() || ! $this->isOperate() ) {
			message( '你没有访问模块的权限', 'back', 'error' );
		}
	}

	/**
	 * 获取站点管理员角色
	 *
	 * @param array $role 角色类型：owner: 所有者 manage: 管理员  operate: 操作员
	 * @param int $siteId 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteRole( array $role, $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;
		$field  = 'uid,groupid,username,role';
		$users  = UserModel::join( 'site_user', 'user.uid', '=', 'site_user.uid' )
		                   ->whereIn( 'role', $role )->where( 'siteid', $siteId )->lists( $field );

		return $users ?: [ ];

	}

	/**
	 * 获取站长信息
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteOwner( $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;
		$uid    = Db::table( 'site_user' )->where( 'siteid', $siteId )->where( 'role', 'owner' )->pluck( 'uid' );
		if ( $uid ) {
			return Db::table( 'user' )->find( $uid );
		}
	}

	/**
	 * 删除用户
	 *
	 * @param int $uid 会员编号
	 *
	 * @return bool
	 */
	public function remove( $uid ) {
		if ( $this->isSuperUser() ) {
			$relationDeleteTable = [
				'user',//用户表
				'site_user',//站点管理员
				'user_permission',//用户管理权限
				'user_profile',//用户字段信息
				'log',//日志记录
			];
			foreach ( $relationDeleteTable as $table ) {
				Db::table( $table )->where( 'uid', $uid )->delete();
			}

			return true;
		}
		$this->error = '只有系统管理员可以删除用户';

		return false;
	}

	/**
	 * 获取用户拥有的站点数量
	 *
	 * @param $uid
	 *
	 * @return mixed
	 */
	public function siteNums( $uid = 0 ) {
		$uid = $uid ?: v( "user.info.uid" );

		return Db::table( 'site_user' )->where( 'uid', $uid )->where( 'role', 'owner' )->count() * 1;
	}

	/**
	 * 检测当前帐号是否能添加站点
	 *
	 * @param int $uid 用户编号
	 *
	 * @return bool
	 */
	public function hasAddSite( $uid = 0 ) {
		$uid  = $uid ?: v( 'user.info.uid' );
		$user = Db::table( 'user' )->find( $uid );
		if ( $user ) {
			//系统管理员不受限制
			if ( $this->isSuperUser( $user['uid'] ) ) {
				return true;
			} else {
				//普通用户检查
				$maxsite = Db::table( 'user_group' )->where( 'id', $user['groupid'] )->pluck( 'maxsite' );

				return $maxsite - $this->siteNums( $uid ) > 0 ? true : false;
			}
		}
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
		if ( $this->isSuperUser() ) {
			return '系统管理员';
		}
		$role = $this->where( 'siteid', $siteid )->where( 'uid', $uid )->pluck( 'role' );
		$data = [ 'owner' => '所有者', 'manage' => '管理员', 'operate' => '操作员' ];

		return $role ? $data[ $role ] : '';
	}

	/**
	 * 设置站点的站长
	 * 站长拥有所有权限
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return int 自增主键
	 */
	public function setSiteOwner( $siteId, $uid ) {
		//系统管理员不添加数据
		if ( $this->isSuperUser( $uid ) ) {
			return true;
		}
		$SiteUser           = new SiteUser();
		$SiteUser['siteid'] = $siteId;
		$SiteUser['role']   = 'owner';
		$SiteUser['uid']    = $uid;

		return $SiteUser->save();
	}

	/**
	 * 获取用户的用户组信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserGroup( $uid = 0 ) {
		$uid      = $uid ?: v( 'user.info.uid' );
		$group_id = Db::table( 'user' )->where( 'uid', $uid )->pluck( 'groupid' );

		return Db::table( 'user_group' )->find( $group_id );
	}

	/**
	 * 获取用户在站点的权限分配
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getUserAtSiteAccess( $siteId = 0, $uid = 0 ) {
		$siteId     = $siteId ?: SITEID;
		$uid        = $uid ?: v( 'user.info.uid' );
		$permission = Db( 'user_permission' )->where( 'siteid', $siteId )->where( 'uid', $uid )->lists( 'type,permission' ) ?: [ ];
		foreach ( $permission as $m => $p ) {
			$permission[ $m ] = explode( '|', $p );
		}

		return $permission;
	}
}