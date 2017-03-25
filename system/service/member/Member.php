<?php namespace system\service\member;

use houdunwang\validate\Validate;
use system\model\MemberAuth;
use system\service\Common;
use system\model\Member as MemberModel;

//服务功能类
class Member extends Common {

	/**
	 * 用户登录检测
	 *
	 * @param bool $return 发生错误时的处理方式 true:返回验证结果 false:直接处理
	 *
	 * @return bool
	 */
	public function isLogin( $return = false ) {
		if ( Session::get( "member_uid" ) ) {
			$this->initMemberInfo();

			return true;
		}
		if ( $return ) {
			return false;
		}
		message( '请登录后操作', url( 'entry/login', [ 'from' => __URL__ ], 'ucenter' ), 'error' );
	}

	//初始用户信息
	public function initMemberInfo() {
		if ( $member_uid = Session::get( "member_uid" ) ) {
			$user          = [];
			$user['info']  = Db::table( 'member' )->where( 'siteid', siteid() )->find( $member_uid );
			$user['group'] = Db::table( 'member_group' )->where( 'id', $user['info']['group_id'] )->first();
			v( 'member', $user );
		}
	}

	//获取会员组
	public function getGroupName( $uid = 0 ) {
		$uid = $uid ?: v( 'user.uid' );
		$sql = "SELECT title,id FROM " . tablename( 'member' ) . " m JOIN " . tablename( 'member_group' ) . " g ON m.group_id = g.id WHERE m.uid={$uid}";
		$d   = Db::query( $sql );

		return $d ? $d[0] : null;
	}

	/**
	 * 判断当前uid的用户是否在当前站点中存在
	 *
	 * @param $uid 会员编号
	 *
	 * @return bool
	 */
	public function hasUser( $uid ) {
		if ( ! Db::table( 'member' )->where( 'siteid', SITEID )->where( 'uid', $uid )->get() ) {
			message( '当前站点中不存在此用户', 'back', 'error' );
		}

		return true;
	}

	/**
	 * 微信自动登录
	 *
	 * @param string $url 登录成功后的跳转地址
	 *
	 * @return bool
	 */
	public function weChatLogin( $url = '' ) {
		if ( IS_WEIXIN && v( 'site.wechat.level' ) >= 3 ) {
			//认证订阅号或服务号,并且开启自动登录时获取微信帐户openid自动登录
			if ( $info = \WeChat::instance( 'oauth' )->snsapiUserinfo() ) {
				$auth = MemberAuth::where( 'wechat', $info['openid'] )->first();
				if ( ! $auth ) {
					//帐号不存在时使用openid添加帐号
					$user             = new MemberModel();
					$user['nickname'] = $info['nickname'];
					$user['icon']     = $info['headimgurl'];
					$user['group_id'] = $this->getDefaultGroup();
					$user->save();
					$model           = new MemberAuth();
					$model['uid']    = $user['uid'];
					$model['wechat'] = $info['openid'];
					$model->save();
				}
				Session::set( 'member_uid', $auth['uid'] );

				return true;
			}
		}
		message( '微信登录失败,请检查微信公众号是否验证', 'back', 'error' );
	}

	/**
	 * 微信扫码登录
	 *
	 * @param string $url 登录成功后的跳转地址
	 */
	public function qrLogin( $url = '' ) {
		WeChat::instance( 'Oauth' )->qrLogin( function ( $info ) use ( $url ) {
			$auth = MemberAuth::where( 'wechat', $info['openid'] )->first();
			if ( ! $auth ) {
				//帐号不存在时使用openid添加帐号
				$user             = new MemberModel();
				$user['nickname'] = $info['nickname'];
				$user['icon']     = $info['headimgurl'];
				$user['group_id'] = $this->getDefaultGroup();
				$user->save();
				$model           = new MemberAuth();
				$model['uid']    = $user['uid'];
				$model['wechat'] = $info['openid'];
				$model->save();
			}
			Session::set( 'member_uid', $auth['uid'] );
			$url = $url ?: Session::get( 'from', url( 'member.index', '', 'ucenter' ) );
			Session::del( 'from' );
			go( $url );
		} );
	}

	//根据access_token获取用户信息
	public function getUserInfoByAccessToken( $access_token ) {
		$res = Db::table( 'member' )->where( 'access_token', $access_token )->first();

		return $res ? [ 'valid' => 1, 'data' => $res ] : [ 'valid' => 0, 'message' => '用户不存在' ];
	}

	/**
	 * 会员登录
	 *
	 * @param $data
	 * @param bool $return 错误处理方式:true 返回错误内容 false直接显示错误
	 *
	 * @return bool
	 */
	public function login( $data, $return = false ) {
		Validate::make( [
			[ 'password', 'required', '密码不能为空' ],
			[ 'code', 'captcha', '验证码输入错误', Validate::EXISTS_VALIDATE ]
		] );
		$member       = new MemberModel();
		$user         = $member->where( 'email', $data['username'] )->orWhere( 'mobile', $data['username'] )->first();
		$errorMessage = '';
		if ( empty( $user ) ) {
			$errorMessage = '帐号不存在';
		}
		if ( md5( $data['password'] . $user['security'] ) != $user['password'] ) {
			$errorMessage = '密码输入错误';
		}
		//错误处理
		if ( $errorMessage ) {
			if ( $return ) {
				return $errorMessage;
			} else {
				message( $errorMessage, '', 'error' );
			}
		}
		\Session::set( 'member_uid', $user['uid'] );

		return true;
	}

	//注册页面
	public function register( $data ) {
		$model = new MemberModel();
		Validate::make( [
			[ 'password', 'required|minlen:5', '密码长度不能小于5位' ],
			[ 'code', 'captcha', '验证码输入错误', Validate::EXISTS_VALIDATE ]
		] );
		//批量添加字段
		foreach ( $data as $k => $v ) {
			$model[ $k ] = $v;
		}
		$info = $this->getPasswordAndSecurity( $data['password'] );
		if ( empty( $info['password'] ) ) {
			message( '密码不能为空', 'back', 'error' );
		}
		if ( isset( $data['cpassword'] ) && $data['password'] !== $data['cpassword'] ) {
			message( '两次密码输入不一致', '', 'error' );
		}
		$model['password'] = $info['password'];
		$model['security'] = $info['security'];
		switch ( v( 'site.setting.register' ) ) {
			case 1:
				//手机号注册
				if ( empty( $data['username'] ) || ! preg_match( '/^\d{11}$/', $data['username'] ) ) {
					message( '请输入手机号', 'back', 'error' );
				}
				$model['mobile'] = $data['username'];
				if ( Db::table( 'member' )->where( 'mobile', $data['mobile'] )->where( 'siteid', siteid() )->get() ) {
					message( '手机号已经存在', 'back', 'error' );
				}
				break;
			case 2:
				//邮箱注册
				if ( empty( $data['username'] ) || ! preg_match( '/\w+@\w+/', $data['username'] ) ) {
					message( '请输入邮箱', 'back', 'error' );
				}
				$model['email'] = $data['username'];
				if ( Db::table( 'member' )->where( 'email', $data['email'] )->where( 'siteid', siteid() )->get() ) {
					message( '邮箱已经存在', 'back', 'error' );
				}

				break;
			case 3:
				//二者都行
				if ( ! preg_match( '/^\d{11}$/', $_POST['username'] ) && ! preg_match( '/\w+@\w+/', $data['username'] ) ) {
					message( '请输入邮箱或手机号', 'back', 'error' );
				} else if ( preg_match( '/^\d{11}$/', $data['username'] ) ) {
					$model['mobile'] = $data['username'];
					if ( empty( $data['username'] ) || Db::table( 'member' )->where( 'mobile', $data['mobile'] )->where( 'siteid', siteid() )->get() ) {
						message( '手机号已经存在', 'back', 'error' );
					}
				} else {
					$model['email'] = $data['username'];
					if ( empty( $data['username'] ) || Db::table( 'member' )->where( 'email', $data['email'] )->where( 'siteid', siteid() )->get() ) {
						message( '邮箱已经存在', 'back', 'error' );
					}
				}
		}
		$model->save();

		return true;
	}

	/**
	 * 获取当前登录会员默认地址
	 * @return mixed
	 */
	public function getDefaultAddress() {
		return Db::table( 'member_address' )->where( 'uid', v( 'member.info.uid' ) )->where( 'siteid', SITEID )->where( 'isdefault', 1 )->first();
	}

	/**
	 * 获取站点的默认会员组编号
	 * @return int|null
	 */
	public function getDefaultGroup() {
		return Db::table( 'member_group' )->where( 'siteid', SITEID )->where( 'isdefault', 1 )->pluck( 'id' );
	}

	/**
	 * 根据密码获取密钥与加密后的密码数据及确认密码
	 *
	 * @param $password 密码
	 *
	 * @return array
	 */
	public function getPasswordAndSecurity( $password ) {
		$data = [ 'security' => '', 'password' => '' ];
		if ( empty( $password ) ) {
			return $data;
		}
		$data             = [];
		$data['security'] = substr( md5( time() ), 0, 10 );
		$data['password'] = md5( $password . $data['security'] );

		return $data;
	}

	/**
	 * 获取站点的所有用户组
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteAllMemberGroup( $siteid = 0 ) {
		$siteid = $siteid ?: siteid();

		return Db::table( 'member_group' )->where( 'siteid', $siteid )->get();
	}
}