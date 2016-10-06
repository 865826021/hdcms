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

//管理员接口
use hdphp\model\Model;

class User extends Model {
	protected $table            = 'user';
	protected $denyInsertFields = [ 'uid' ];
	protected $validate
	                            = [
			[ 'username', 'required', '用户名不能为空', self::EXIST_VALIDATE, self::MODEL_INSERT ],
			[ 'username', 'regexp:/^[a-z][\w@]+$/i', '用户名必须是字母,数字,下划线或 @ 符号,并且必须以字母开始', self::EXIST_VALIDATE, self::MODEL_INSERT ],
			[ 'username', 'unique', '用户名已经存在', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'groupid', 'required', '用户组不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'password', 'required', '密码不能为空', self::NOT_EMPTY_VALIDATE, self::MODEL_INSERT ],
			[ 'password', 'minlen:6', '请输入不少于6位的密码', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'qq', 'regexp:/^\d+$/', '请输入正确的QQ号', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'qq', 'unique', 'QQ号已经被使用', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'email', 'email', '邮箱格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_INSERT ],
			[ 'email', 'unique', '邮箱已经被注册', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'regexp:/^\d{11}$/', '手机号格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'unique', '手机号已经被其他用户注册', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		];

	protected $auto
		= [
			[ 'groupid', 'autoGroupId', 'method', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'password', 'autoPassword', 'method', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'regtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'mobile_valid', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'email_valid', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'regip', 'clientIp', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'lasttime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'lastip', 'clientIp', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'starttime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'starttime', 'strtotime', 'function', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'endtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'endtime', 'strtotime', 'function', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'remark', '', 'string', self::MUST_AUTO, self::MODEL_INSERT ],
		];
	protected $filter
		= [
			[ 'password', self::EMPTY_FILTER, self::MODEL_BOTH ],
		];
	/**
	 * 删除用户时关联删除数据的表
	 * @var array
	 */
	protected $relationDeleteTable
		= [
			'user',//用户表
			'site_user',//站点管理员
			'user_permission',//用户管理权限
			'user_profile',//用户字段信息
		];

	/**
	 * 密码自动完成处理
	 *
	 * @param $val
	 * @param $data
	 *
	 * @return string
	 */
	protected function autoPassword( $val, &$data ) {
		$data['security'] = substr( md5( time() ), 0, 10 );

		return md5( $val . $data['security'] );
	}

	/**
	 * 删除用户
	 * @return bool
	 */
	public function remove() {
		foreach ( $this->relationDeleteTable as $t ) {
			Db::table( $t )->where( 'uid', $this->uid )->delete();
		}

		return TRUE;
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
			$this->error = '密码输入错误';

			return FALSE;
		}

		if ( ! $user['status'] ) {
			$this->error = '您的帐号正在审核中';

			return FALSE;
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
	 * @param $password 登录密码
	 * @param $username 用户名
	 *
	 * @return bool
	 */
	public function checkPassword( $password, $username ) {
		$user = Db::table( 'user' )->where( 'username', $username )->first();

		return $user['password'] == md5( $password . $user['security'] );
	}

	/**
	 * 更新管理员SESSION数据
	 */
	public function updateUserSessionData() {
		$user = Db::table( 'user' )->where( 'uid', Session::get( 'user.uid' ) )->find();
		Session::set( 'user', $user );
	}

	/**
	 * 后台管理用户登录检测
	 * @return bool
	 */
	public function isLogin( $type = 'deal' ) {
		if ( ! Session::get( 'admin_uid' ) ) {
			if ( $type == 'deal' ) {
				message( '请登录后进行操作', u( 'system/entry/login' ), 'error' );
			} else {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * 会员组表关联
	 * @return mixed
	 */
	public function userGroup() {
		return $this->belongsTo( 'system\model\UserGroup', 'groupid' );
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
	 * 验证当前用户在当前站点
	 * 能否使用当前模块
	 * 具体模块动作需要使用权限标识独立验证
	 * @return bool
	 * @throws \Exception
	 */
	public function verifyModuleAccess() {
		//操作员验证
		if ( ! $this->isOperate() ) {
			return FALSE;
		}
		if ( v( "module.is_system" ) == 1 ) {
			return TRUE;
		} else {
			//站点是否含有模块
			if ( ! ( new Site() )->hasModule( SITEID, v( 'module.name' ) ) ) {
				return FALSE;
			}
			//插件模块
			$allowModules = Db::table( 'user_permission' )->where( 'siteid', SITEID )->where( 'uid', Session::get( 'user.uid' ) )->lists( 'type' );
			if ( ! empty( $allowModules ) ) {
				return in_array( v( 'module.name' ), $allowModules );
			}

			return TRUE;
		}
	}

	/**
	 * 获取站长用户信息
	 *
	 * @param $siteid 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteOwner( $siteid ) {
		$uid = Db::table( 'site_user' )->where( 'siteid', $siteid )->where( 'role', 'owner' )->pluck( 'uid' );

		return Db::table( 'user' )->find( $uid );
	}

	/**
	 * 是否为站长
	 * 当前帐号是否拥有站长权限(网站所有者,系统管理员)
	 *
	 * @param int $siteid 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool
	 */
	public function isOwner( $siteid = NULL, $uid = NULL ) {
		$siteid = $siteid ?: SITEID;
		$uid    = $uid ?: Session::get( "user.uid" );
		if ( ! ( new Site() )->isSite( $siteid ) || empty( $uid ) ) {
			return FALSE;
		}
		if ( $this->isSuperUser( $uid, 'return' ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )->where( 'siteid', $siteid )->where( 'uid', $uid )->where( 'role', 'owner' )->get() ? TRUE : FALSE;
	}

	/**
	 * 是否为管理员
	 * 是否拥有管理员及以上权限(网站所有者,系统管理员)
	 *
	 * @param int $siteid 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function isManage( $siteid = NULL, $uid = NULL ) {
		$siteid = $siteid ?: SITEID;
		$uid    = $uid ?: v( "user.uid" );
		if ( ! ( new Site() )->isSite( $siteid ) || empty( $uid ) ) {
			return FALSE;
		}
		if ( $this->isSuperUser( $uid, 'return' ) ) {
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
	 * @param int $siteid 站点编号
	 * @param int $uid 用户编号 默认使用当前登录的帐号
	 *
	 * @return bool|string
	 */
	public function isOperate( $siteid = NULL, $uid = NULL ) {
		$siteid = $siteid ?: SITEID;
		$uid    = $uid ?: Session::get( "user.uid" );
		if ( ! ( new Site() )->isSite( $siteid ) || empty( $uid ) ) {
			return FALSE;
		}
		if ( ( new User() )->isSuperUser( $uid, 'return' ) ) {
			return TRUE;
		}

		return Db::table( 'site_user' )
		         ->where( 'siteid', $siteid )
		         ->where( 'uid', $uid )
		         ->WhereIn( 'role', [ 'owner', 'manage', 'operate' ] )
		         ->get() ? TRUE : FALSE;
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
}