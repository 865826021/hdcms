<?php namespace app\system\controller;

use houdunwang\validate\Validate;

/**
 * 后台登录/退出
 * Class entry
 * @package system\controller
 */
class Entry {
	//注册
	public function register() {
		//系统维护检测
		service( 'user' )->checkSystemClose();
		if ( IS_POST ) {
			Validate::make( [
				[ 'code', 'captcha', '验证码输入错误', 1 ],
				[ 'password', 'confirm:password2', '两次密码输入不一致', 3 ]
			] );
			//默认用户组
			$User             = new User();
			$User['username'] = Request::post( 'username' );
			//用户组过期时间
			$daylimit        = Db::table( 'user_group' )->where( 'id', v( 'config.register.groupid' ) )->pluck( 'daylimit' );
			$User['endtime'] = time() + $daylimit * 3600 * 24;
			//获取密码与加密密钥
			$info             = $User->getPasswordAndSecurity();
			$User['password'] = $info['password'];
			$User['security'] = $info['security'];
			$User['email']    = Request::post( 'email' );
			$User['qq']       = Request::post( 'qq' );
			$User['mobile']   = Request::post( 'mobile' );
			$User['groupid']  = v( 'config.register.groupid' );
			$User['status']   = v( 'config.register.audit' );
			if ( ! $User->save() ) {
				message( $User->getError(), 'back', 'error' );
			}
			message( '注册成功,请登录系统', u( 'login', [ 'from' => $_GET['from'] ] ) );
		}

		return view();
	}

	/**
	 * 后台帐号登录
	 *
	 * @return mixed
	 */
	public function login() {
		if(IS_POST){
			$f = File::upload();
			dd($f);
		}
		RETURN view();
		exit;
		if ( IS_POST ) {
			Validate::make( [
				[ 'username', 'required', '用户名不能为空', Validate::MUST_VALIDATE ],
				[ 'password', 'required', '密码不能为空', Validate::MUST_VALIDATE ],
				[ 'code', 'captcha', '验证码输入错误', Validate::EXISTS_VALIDATE ],
			] );
			//会员登录
			if ( !\User::login( Request::post() ) ) {
				message( \User::getError(), 'back', 'error' );
			}
			//系统维护检测
			\User::checkSystemClose();

			message( '登录成功,系统准备跳转', q( 'get.from', u( 'system/site/lists' ) ) );
		}
		if ( Session::get( 'user.uid' ) ) {
			go( 'system/site/lists' );
		}

		return view();
	}

	//验证码
	public function code() {
		Code::make();
	}

	//退出
	public function quit() {
		Session::flush();
		message( '退出系统成功,系统将自动进行跳转', q( 'get.from', u( 'login' ) ) );
	}
}