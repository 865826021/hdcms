<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace api;

//会员服务
class member {
	//检测会员登录&微信端开启自动登录时使用openid登录
	public function isLogin() {
		if ( ! Session::get( 'member' ) ) {
			//没有登录并且是微信客户端时使用openid登录
			$this->loginByOpenid();
			go( u( 'uc/reg/login', [ 'i' => v( 'site.siteid' ) ] ) );
		}
	}

	//使用openid自动登录
	public function loginByOpenid() {
		//认证订阅号或服务号,并且开启自动登录时获取微信帐户openid自动登录
		if ( IS_WEIXIN && v( 'wechat.level' ) >= 3 && v( 'setting.register.focusreg' ) == 1 ) {
			$info = Weixin::instance( 'oauth' )->snsapiUserinfo();
			$user = Db::table( 'member' )->where( 'openid', $info['openid'] )->first();
			if ( ! $user ) {
				//帐号不存在时使用openid添加帐号
				$data['openid']   = $info['openid'];
				$data['nickname'] = $info['nickname'];
				$data['icon']     = $info['headimgurl'];
				$data['siteid']   = v( 'site.siteid' );
				$data['group_id'] = Db::table( 'member_group' )->where( 'siteid', v( 'site.siteid' ) )->where( 'isdefault', 1 )->pluck( 'id' );
				$uid              = Db::table( 'member' )->insertGetId( $data );
				$user             = Db::table( 'member' )->where( 'uid', $uid )->first();
			}
			Session::set( 'member', $user );
			$backurl = q( 'get.backurl', u( 'uc/entry/home', [ 'i' => $_GET['i'] ] ), 'htmlentities' );
			go( $backurl );
		}
	}

	//获取会员组
	public function getGroupName( $uid ) {
		$sql = "SELECT title,id FROM " . tablename( 'member' ) . " m JOIN " . tablename( 'member_group' ) . " g ON m.group_id = g.id WHERE m.uid={$uid}";
		$d   = Db::select( $sql );

		return $d ? $d[0] : NULL;
	}

	//更新会员SESSION信息
	public function updateUserSessionData() {
		$user = Db::table( 'member' )->where( 'uid', Session::get( 'member.uid' ) )->first();
		Session::set( 'member', $user );
	}



}