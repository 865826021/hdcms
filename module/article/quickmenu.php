<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\article;

use module\hdSite;

/**
 * 微站快捷导航菜单
 * Class quickmenu
 * @package module\article
 */
class quickmenu extends hdSite {

	public function doSitePost() {
		$web_id = q( 'get.webid', 0, 'intval' );
		if ( IS_POST ) {
			$data               = json_decode( $_POST['data'], TRUE );
			$data['params']     = json_encode( $data['params'] );
			$data['type']       = 1;
			$data['status']     = q( 'post.status', 0, 'intval' );
			$data['createtime'] = time();
			if ( Db::table( 'web_page' )->where( 'siteid', '=', Session::get( 'siteid' ) )->get() ) {
				Db::table( 'web_page' )->where( 'siteid', '=', Session::get( 'siteid' ) )->update( $data );
			} else {
				Db::table( 'web_page' )->insert( $data );
			}
			message( '保存快捷菜单成功', 'refresh', 'success' );
		}
		$field = Db::table( 'web_page' )->where( 'siteid', '=', Session::get( 'siteid' ) )->where( 'title', '类型:快捷导航' )->first();
		if ( ! $field ) {
			$field = [
				'siteid'      => v( 'site.siteid' ),
				'web_id'      => $web_id,
				'title'       => '类型:快捷导航',
				'description' => '',
				'status'      => 1,
				'params'      => [
					'style'       => 'quickmenu_normal',
					'menus'       => [ ],
					'modules'     => [ ],
					'has_ucenter' => 1,//会员中心
					'has_home'    => 1,//微站主页
					'has_special' => 1,//微页面
					'has_article' => 1,//文章及分类
				]
			];
		} else {
			$field           = Arr::string_to_int( $field );
			$field['params'] = json_decode( $field['params'] );
		}
		View::with( 'field', $field );
		View::with( 'modules', v('modules'));
		View::make( $this->template . '/quickmenu/post.php' );
	}

}