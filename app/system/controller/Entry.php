<?php namespace app\system\controller;

use houdunwang\validate\Validate;
use system\model\User;

/**
 * 后台登录/退出
 * Class entry
 * @package system\controller
 */
class Entry {
	/**
	 * 通过域名访问时执行的方法
	 * 首先根据域名判断该域名是否是站点的默认域名
	 * 然后通过域名执行默认模块
	 * @return mixed
	 */
	public function home() {
		$domain = Db::table( 'module_domain' )->where( 'domain', $_SERVER['SERVER_NAME'] )->first();
		if ( $domain && ! empty( $domain['module'] ) ) {
			//站点设置了默认访问模块时访问模块的桌面入口页面
			$module = Db::table( 'modules_bindings' )
			            ->join( 'modules', 'modules.name', '=', 'modules_bindings.module' )
			            ->join( 'module_domain', 'module_domain.module', '=', 'modules.name' )
			            ->where( 'module_domain.siteid', $domain['siteid'] )
			            ->where( 'entry', 'web' )->first();
			if ( $module && ! empty( $module['do'] ) ) {
				$class = ( $module['is_system'] ? 'module' : 'addons' ) . '\\' . $module['module'] . '\system\Navigate';
				if ( class_exists( $class ) && method_exists( $class, $module['do'] ) ) {
					Request::set( 'get.siteid', $domain['siteid'] );
					Request::set( 'get.m', $module['module'] );
					//初始站点数据
					\Site::siteInitialize();
					//初始模块数据
					\Module::moduleInitialize();

					return call_user_func_array( [ new $class, $module['do'] ], [ ] );
				}
			}
		}

		return view();
	}

	//注册
	public function register() {
		if ( IS_POST ) {
			Validate::make( [
				[ 'code', 'captcha', '验证码输入错误', Validate::EXISTS_VALIDATE ],
				[ 'password', 'confirm:password2', '两次密码输入不一致', Validate::MUST_VALIDATE ]
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
			message( '注册成功,请登录系统', u( 'login', [ 'from' => q( 'get.form' ) ] ) );
		}

		return view();
	}

	/**
	 * 后台帐号登录
	 *
	 * @return mixed
	 */
	public function login() {
		if ( IS_POST ) {
			Validate::make( [
				[ 'username', 'required', '用户名不能为空', Validate::MUST_VALIDATE ],
				[ 'password', 'required', '密码不能为空', Validate::MUST_VALIDATE ],
				[ 'code', 'captcha', '验证码输入错误', Validate::EXISTS_VALIDATE ],
			] );
			//会员登录
			\User::login( Request::post() );
			//系统维护检测
			\User::checkSystemClose();
			//跳转URL地址
			$url = q( 'get.form' );
			if ( empty( $url ) ) {
				switch ( Session::get( 'system.login' ) ) {
					case 'hdcms':
						//系统管理平台
						$url = u( 'system/site/lists' );
						break;
					case 'admin':
						//站点管理平台
						$site = Db::table( 'module_domain' )->where( 'domain', $_SERVER['SERVER_NAME'] )->first();
						$url  = __ROOT__ . '?s=site/entry/home&siteid=' . $site['siteid'];
						break;
				}
			}
			message( '登录成功,系统准备跳转', $url );
		}
		if ( Session::get( 'user.uid' ) ) {
			go( 'system/site/lists' );
		}

		return view('app/system/view/entry/login.php');
	}

	//验证码
	public function code() {
		Code::make();
	}

	//退出
	public function quit() {
		Session::flush();
		$url = __WEB__ . '/' . q( 'session.system.login', 'hdcms' );
		go($url);
	}
}