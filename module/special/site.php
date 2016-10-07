<?php namespace module\special;

use module\hdSite;

/**
 * 微信关注时或默认回复内容设置
 *
 * @author 后盾网
 * @url http://open.hdcms.com
 */
class site extends hdSite {
	//系统消息回复设置
	public function doSitePost() {
		service( 'user' )->auth( 'reply_special', 'system' );
		if ( IS_POST ) {
			$data['welcome']         = $_POST['welcome'];
			$data['default_message'] = $_POST['default'];
			Db::table( 'site_setting' )->where( 'siteid', SITEID )->update( $data );
			service( 'site' )->updateCache();
			message( '系统回复消息设置成功', '', 'success' );
		}

		return view( $this->template . '/post.html' );
	}
}