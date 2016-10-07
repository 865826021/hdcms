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
 * 会员接口
 * Class Member
 * @package server
 * @author 向军
 */
class Member {
	protected $db;

	public function __construct() {
		$this->db = new \system\model\Member();
	}

	//检测用户登录
	public function isLogin() {
		if ( ! Session::get( "member.uid" ) ) {
			message( '请登录后操作', web_url( 'reg/login', [ ], 'uc' ), 'error' );
		}

		return TRUE;
	}

	//获取会员组
	public function getGroupName( $uid ) {
		$sql = "SELECT title,id FROM " . tablename( 'member' ) . " m JOIN " . tablename( 'member_group' ) . " g ON m.group_id = g.id WHERE m.uid={$uid}";
		$d   = Db::query( $sql );

		return $d ? $d[0] : NULL;
	}

	/**
	 * 判断当前uid的用户是否在当前站点中存在
	 *
	 * @param $uid 会员编号
	 *
	 * @return bool
	 */
	public function hasUser( $uid ) {
		if ( ! Db::table('member')->where( 'siteid', SITEID )->where( 'uid', $uid )->get() ) {
			message( '当前站点中不存在此用户', 'back', 'error' );
		}

		return TRUE;
	}

	//微信自动登录
	public function weixinLogin() {
		if ( IS_WEIXIN && v( 'wechat.level' ) >= 3 ) {
			//认证订阅号或服务号,并且开启自动登录时获取微信帐户openid自动登录
			if ( $info = \Weixin::instance( 'oauth' )->snsapiUserinfo() ) {
				$user = $this->db->where( 'openid', $info['openid'] )->first();
				if ( ! $user ) {
					//帐号不存在时使用openid添加帐号
					$data['openid']   = $info['openid'];
					$data['nickname'] = $info['nickname'];
					$data['icon']     = $info['headimgurl'];
					if ( ! $uid = $this->db->add( $data ) ) {
						return [ 'valid' => 0, 'message' => $this->db->getError() ];
					}
					$user = $this->db->find( $uid );
				}
				//更新access_token
				$user['access_token'] = md5( $user['username'] . $user['password'] . c( 'app.key' ) );
				$this->db->where( 'uid', $user['uid'] )->update( [ 'access_token' => $user['access_token'] ] );
				Session::set( 'member', $user );

				return [ 'valid' => 1, 'data' => $user ];
			}
		}
	}

	//根据access_token获取用户信息
	public function getUserInfoByAccessToken( $access_token ) {
		$res = $this->db->where( 'access_token', $access_token )->first();

		return $res ? [ 'valid' => 1, 'data' => $res ] : [ 'valid' => 0, 'message' => '用户不存在' ];
	}

	//会员登录
	public function login( $data ) {
		$user = $this->db->where( 'email', $data['username'] )->orWhere( 'mobile', $data['username'] )->first();
		if ( empty( $user ) ) {
			return [ 'valid' => 0, 'message' => '帐号不存在' ];
		}
		if ( md5( $data['password'] . $user['security'] ) != $user['password'] ) {
			return [ 'valid' => 0, 'message' => '密码输入错误' ];
		}
		Session::set( 'member', $user );

		return [ 'valid' => 1, 'data' => $user ];
	}

	//注册页面
	public function register( $data ) {
		switch ( v( 'setting.register.item' ) ) {
			case 1:
				//手机号注册
				if ( ! preg_match( '/^\d{11}$/', $data['username'] ) ) {
					message( '请输入手机号', 'back', 'error' );
				}
				$data['mobile'] = $data['username'];
				break;
			case 2:
				//邮箱注册
				if ( ! preg_match( '/\w+@\w+/', $data['username'] ) ) {
					message( '请输入邮箱', 'back', 'error' );
				}
				$data['email'] = $data['username'];
				break;
			case 3:
				//二者都行
				if ( ! preg_match( '/^\d{11}$/', $_POST['username'] ) && ! preg_match( '/\w+@\w+/', $data['username'] ) ) {
					message( '请输入邮箱或手机号', 'back', 'error' );
				} else if ( preg_match( '/^\d{11}$/', $_POST['username'] ) ) {
					$data['mobile'] = $data['username'];
				} else {
					$data['email'] = $data['username'];
				}
		}
		if ( ! empty( $data['mobile'] ) ) {
			if ( $this->db->Where( 'mobile', $data['mobile'] )->get() ) {
				return [ 'valid' => 0, 'message' => '手机号已经存在' ];
			}
		}
		if ( ! empty( $data['email'] ) ) {
			if ( $this->db->Where( 'mobile', $data['email'] )->get() ) {
				return [ 'valid' => 0, 'message' => '邮箱已经存在' ];
			}
		}

		if ( ! $uid = $this->db->add( $data ) ) {
			return [ 'valid' => 0, 'message' => $this->db->getError() ];
		}

		return [ 'valid' => 1, 'data' => $this->db->find( $uid ) ];
	}

	/**
	 * 获取当前登录会员默认地址
	 * @return mixed
	 */
	public function getDefaultAddress() {
		return $this->where( 'uid', Session::get( 'member.uid' ) )->where( 'siteid', SITEID )->where( 'isdefault', 1 )->first();
	}

	/**
	 * 默认会员组编号
	 * @return mixed
	 */
	public function defaultGruopId() {
		return Db::table( 'member_group' )->where( 'siteid', SITEID )->where( 'isdefault', 1 )->pluck( 'id' );
	}
}