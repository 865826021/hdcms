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
			$data   = json_decode( $_POST['data'], TRUE );
			$action = isset( $data['id'] ) ? 'save' : 'add';
			if ( ! $webPage->$action( $data ) ) {
				message( $webPage->getError(), 'back', 'error' );
			}
			message( '保存快捷菜单成功', 'refresh', 'success' );
		}
		$field = $webPage->where( 'siteid', SITEID )->where( 'type', 1 )->first();
		if ( ! $field ) {
			$field = [
				'siteid'      => SITEID,
				'web_id'      => 0,
				'title'       => '类型:快捷导航',
				'description' => '',
				'type'        => 1,//页面类型  1 导航菜单
				'status'      => 1,
				'params'      => [
					'style'           => 'quickmenu_normal',
					'menus'           => [ ],
					'modules'         => [ ],
					'has_home_button' => 1,
					'has_ucenter'     => 1,//会员中心
					'has_home'        => 1,//微站主页
					'has_special'     => 1,//微页面
					'has_article'     => 1,//文章及分类
				]
			];
		} else {
			$field           = Arr::string_to_int( $field );
			$field['params'] = json_decode( $field['params'] );
		}
		View::with( 'field', $field );
		View::with( 'modules', v( 'modules' ) );
		View::make( $this->template . '/quickmenu/post.php' );
	}

}