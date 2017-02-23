<?php namespace app\system\controller;

use system\model\User;
use system\model\UserPermission;

/**
 * 站点权限设置
 * Class Permission
 * @package app\system\controller
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Permission {
	public function __construct() {
		\User::loginAuth();
	}

	//站点管理员设置
	public function users() {
		if ( ! \User::isManage() ) {
			message( '你没有功能的操作权限', 'back', 'error' );
		}
		//获取除站长外的站点操作员
		$users = \User::getSiteRole( [ 'manage', 'operate' ] );
		//移除当前用户,不允许给自己设置权限
		$uid = v( 'user.info.uid' );
		if ( isset( $users[ $uid ] ) ) {
			unset( $users[ $uid ] );
		}
		//站长数据
		$owner = \User::getSiteOwner( SITEID );

		return view()->with( [ 'users' => $users, 'owner' => $owner ] );
	}

	/**
	 * 添加站点操作员
	 * 只有系统管理员或站长可以执行这个功能
	 */
	public function addOperator() {
		if ( \User::isManage() ) {
			foreach ( q( 'post.uid', [ ] ) as $uid ) {
				if ( ! Db::table( "site_user" )->where( "uid", $uid )->where( "siteid", SITEID )->get() ) {
					Db::table( 'site_user' )->insert( [
						'siteid' => SITEID,
						'uid'    => $uid,
						'role'   => 'operate'
					] );
				}
			}
			record( '添加站点操作员' );
			message( '站点操作员添加成功', '', 'success' );
		}
		message( '你没有操作站点的权限', '', 'error' );
	}

	/**
	 * 更改会员的站点角色
	 * 需要站长或系统管理员才可以操作
	 */
	public function changeRole() {
		if ( \User::isManage() ) {
			$uid = \Request::post( 'uid' );
			Db::table( 'site_user' )->where( 'uid', $uid )
			  ->where( 'siteid', SITEID )->update( [ 'role' => $_POST['role'] ] );
			record( '更改了站点管理员角色类型' );
			message( '管理员角色更新成功', '', 'success' );
		} else {
			message( '你没有操作站点的权限', '', 'error' );
		}
	}

	//删除站点用户
	public function removeSiteUser() {
		if ( \User::isManage() ) {
			Db::table( 'site_user' )->where( 'siteid', SITEID )->whereIn( 'uid', Request::post( 'uids' ) )->delete();
			message( '站点管理员删除成功' );
		} else {
			message( '你没有操作站点的权限', '', 'error' );
		}
	}

	//设置菜单权限
	public function menu() {
		if ( ! \User::isManage() ) {
			message( '你没有操作权限', '', 'warning' );
		}
		//设置权限的用户
		$uid = Request::get( 'fromuid' );
		if ( IS_POST ) {
			//删除所有旧的权限
			UserPermission::where( 'siteid', SITEID )->where( 'uid', $uid )->delete();
			//系统权限
			if ( $system = Request::post( 'system' ) ) {
				$model               = new UserPermission();
				$model['siteid']     = SITEID;
				$model['uid']        = $uid;
				$model['type']       = 'system';
				$model['permission'] = implode( '|', $system );
				$model->save();
			}
			//模块权限
			if ( $modules = Request::post( 'modules' ) ) {
				foreach ( $modules as $module => $actions ) {
					$model               = new UserPermission();
					$model['siteid']     = SITEID;
					$model['uid']        = $uid;
					$model['type']       = $module;
					$model['permission'] = implode( '|', $actions );;
					$model->save();
				}
			}
			message( '权限设置成功', 'refresh', 'success' );
		}
		//获取帐号原有权限
		$old = \User::getUserAtSiteAccess( SITEID, $uid );
		/**
		 * 读取系统菜单
		 * 并根据原数据设置状态
		 */
		$menus = Db::table( 'menu' )->get();
		foreach ( $menus as $k => $v ) {
			$menus[ $k ]['checked'] = '';
			if ( isset( $old['system'] ) && in_array( $v['permission'], $old['system'] ) ) {
				$menus[ $k ]['checked'] = " checked='checked'";
			}
		}
		$menusAccess = \Arr::channelLevel( $menus ?: [ ], 0, '', 'id', 'pid' );

		/**
		 * 对扩展模块状态进行设置
		 */
		$allModules = \Module::getSiteAllModules( SITEID, false );
		//模块权限
		$moduleAccess = [ ];
		foreach ( $allModules as $k => $m ) {
			if ( $m['is_system'] == 0 ) {
				//对扩展模块进行处理
				if ( $m['setting'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_setting', '参数设置', $old );
				}
				if ( $m['crontab'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_crontab', '定时任务', $old );
				}
				if ( $m['router'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_router', '路由规则', $old );
				}
				if ( $m['domain'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_domain', '域名设置', $old );
				}
				if ( $m['middleware'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_middleware', '中间件设置', $old );
				}
				if ( $m['rule'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_rule', '回复规则列表', $old );
				}
				if ( $m['rule'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_cover', '封面回复', $old );
				}
				if ( $m['web']['member'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_web_member', '桌面个人中心导航', $old );
				}
				if ( $m['web']['member'] ) {
					$this->formatModuleAccessData( $moduleAccess, $m['name'], 'system_mobile_member', '移动端个人中心导航', $old );
				}
				if ( $m['budings']['business'] ) {
					//控制器业务功能
					foreach ( $m['budings']['business'] as $c ) {
						$this->formatModuleAccessData( $moduleAccess, $m['name'], '', $c['title'], $old );
						foreach ( $c['do'] as $d ) {
							$permission = 'business_' . $c['controller'] . '_' . $d['do'];
							$this->formatModuleAccessData( $moduleAccess, $m['name'], $permission, $d['title'], $old );
						}
					}
				}
			}
		}

		//模块权限
		return view()->with( [
			'menusAccess'  => $menusAccess,
			'moduleAccess' => $moduleAccess
		] );
	}

	/**
	 * 获取权限菜单使用的标准模块数组
	 *
	 * @param array $access 模块标识
	 * @param string $name 模块标识
	 * @param string $permission 标识标识
	 * @param string $title 菜单标题
	 * @param array $old 旧的权限数据
	 *
	 * @return mixed
	 */
	protected function formatModuleAccessData( &$access, $name, $permission, $title, $old ) {
		$data['name']       = "modules[{$name}][]";
		$data['title']      = $title;
		$data['permission'] = $permission;
		$data['checked']    = '';
		if ( isset( $old[ $name ] ) && in_array( $permission, $old[ $name ] ) ) {
			$data['checked'] = "checked='checked'";
		}
		$access[ $name ][] = $data;

		return $access;
	}
}