<?php namespace module\article;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use module\hdSite;
use system\model\WebPage;

/**
 * 微站快捷导航菜单
 * Class quickmenu
 * @package module\article
 */
class quickmenu extends hdSite {
	public function doSitePost() {
		$webPage = new WebPage();
		if ( IS_POST ) {
			$data = json_decode( $_POST['data'], TRUE );
			$webPage->save( $data );
			message( '保存快捷菜单成功', 'refresh', 'success' );
		}
		$field = Db::table( 'web_page' )->where( 'siteid', SITEID )->where( 'type', 1 )->first() ?: [ ];
		if ( $field ) {
			$field           = Arr::string_to_int( $field );
			$field['params'] = json_decode( $field['params'] );
		}

		return view( $this->template . '/quickmenu/post.php' )->with( 'field', $field );
	}

}