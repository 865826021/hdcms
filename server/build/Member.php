<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace server\build;
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

	//更改会员SESSION数据
	public function updateSession() {
		return $this->db->updateUserSessionData();
	}

	//积分修改
	public function changeCredit( $data ) {
		if ( ! $this->db->changeCredit( $data ) ) {
			return $this->db->getError();
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

				return TRUE;
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

		return [ 'valid' => 1, 'data' => $user ];;
	}
}