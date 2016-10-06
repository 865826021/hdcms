<?php namespace system\service\build;

/**
 * 微信管理服务
 * Class WeChat
 * @package system\service
 */
class WeChat {
	/**
	 * 使用微信openid自动登录
	 */
	public function loginByOpenid() {
		//认证订阅号或服务号,并且开启自动登录时获取微信帐户openid自动登录
		if ( $info = \Weixin::instance( 'oauth' )->snsapiUserinfo() ) {
			$user = $this->where( 'openid', $info['openid'] )->first();
			if ( ! $user ) {
				//帐号不存在时使用openid添加帐号
				$data['openid']   = $info['openid'];
				$data['nickname'] = $info['nickname'];
				$data['icon']     = $info['headimgurl'];
				if ( $uid = ! $this->add( $data ) ) {
					message( $this->getError(), 'back', 'error' );
				}
				$user = Db::table( 'member' )->where( 'uid', $uid )->first();
			}
			Session::set( 'member', $user );

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * 获取公众号类型
	 *
	 * @param $level 公众号编号
	 *
	 * @return mixed
	 */
	public function chatNameBylevel( $level ) {
		$data = [ 1 => '普通订阅号', 2 => '普通服务号', 3 => '认证订阅号', 4 => '认证服务号/认证媒体/政府订阅号' ];

		return $data[ $level ];
	}
}