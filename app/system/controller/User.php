<?php namespace app\system\controller;

use system\model\User as UserModel;

/**
 * 用户管理
 * Class User
 * @package app\system\controller
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class User {
	//用户列表
	public function lists() {
		\User::superUserAuth();
		$users = UserModel::leftJoin( 'user_group', 'user.groupid', '=', 'user_group.id' )
		                  ->where( 'user.groupid', '>', '0' )->paginate( 10 );

		return view()->with( 'users', $users );
	}

	//添加用户
	public function add() {
		\User::superUserAuth();
		if ( IS_POST ) {
			Validate::make( [
				[ 'password', 'confirm:password2', '两次密码输入不一致' ]
			] );
			$model             = new UserModel();
			$info              = $model->getPasswordAndSecurity();
			$model['password'] = $info['password'];
			$model['security'] = $info['security'];
			//用户组过期时间
			$daylimit          = Db::table( 'user_group' )->where( 'id', Request::post( 'groupid' ) )->pluck( 'daylimit' );
			$model['endtime']  = time() + $daylimit * 3600 * 24;
			$model['groupid']  = Request::post( 'groupid' );
			$model['username'] = Request::post( 'username' );
			$model['remark']   = Request::post( 'remark' );
			$model->save();
			record( '添加了新用户' . $model['username'] );
			message( '添加新用户成功', 'lists' );
		}
		//获取用户组
		$groups = Db::table( 'user_group' )->get();

		return view()->with( 'groups', $groups );
	}

	//编辑
	public function edit() {
		\User::superUserAuth();
		$model = UserModel::find( Request::get( 'uid' ) );
		if ( IS_POST ) {
			if ( Request::post( 'password' ) ) {
				Validate::make( [
					[ 'password', 'confirm:password2', '两次密码输入不一致' ]
				] );
				//存在密码时设置密码
				$info            = $model->getPasswordAndSecurity();
				$model->password = $info['password'];
				$model->security = $info['security'];
			}
			$model['endtime'] = strtotime( Request::post( 'endtime' ) );
			$model['groupid'] = Request::post( 'groupid' );
			$model['remark']  = Request::post( 'remark' );
			$model['qq']      = Request::post( 'qq' );
			$model['mobile']  = Request::post( 'mobile' );
			$model->save();
			message( '用户资料修改成功', 'lists' );
		}
		//会员组
		$groups = Db::table( 'user_group' )->get();

		return view()->with( [
			'groups' => $groups,
			'user'   => $model
		] );
	}

	//锁定或解锁用户
	public function updateStatus() {
		\User::superUserAuth();
		$model = UserModel::find( Request::get( 'uid' ) );
		if ( \User::isSuperUser( $model->uid ) ) {
			message( '管理员帐号不允许操作', 'back', 'error' );
		}
		//防止修改密码
		unset( $model['password'] );
		$model['status'] = Request::get( 'status', 0 );
		$model->save();
		message( '操作成功', 'back', 'success' );
	}

	//删除用户
	public function remove() {
		\User::superUserAuth();
		if ( ! \User::remove( Request::post( 'uid' ) ) ) {
			message( \User::getError(), 'with' );
		}
		message( '删除用户成功', 'back', 'success' );
	}

	/**
	 * 查看用户权限
	 * @return mixed
	 */
	public function permission() {
		\User::superUserAuth();
		$model = UserModel::find( Request::get( 'uid' ) );
		//获取用户组信息
		$group = $model->userGroup();
		//获取用户站点信息
		$sites = \Site::getUserAllSite( $model['uid'] );
		//用户套餐
		$packages = \Package::getUserGroupPackageLists( $model['groupid'] );

		return view()->with( [
			'group'    => $group,
			'packages' => $packages,
			'sites'    => $sites
		] );
	}

	/**
	 * 修改后台帐号密码
	 * @return mixed
	 */
	public function myPassword() {
		if ( IS_POST ) {
			Validate::make( [
				[ 'password', 'required', '密码不能为空' ],
				[ 'password', 'confirm:password2', '两次密码输入不一致' ]
			] );
			$model             = UserModel::find( v( 'user.info.uid' ) );
			$info              = $model->getPasswordAndSecurity();
			$model['password'] = $info['password'];
			$model['security'] = $info['security'];
			if ( $model->save() ) {
				\Session::flush();
				message( '我的资料修改成功,系统将引导你重新登录', 'system/entry/login', 'success' );
			}
			message( $model->getError(), 'with' );
		}

		return view();
	}
}