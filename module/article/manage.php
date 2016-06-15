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
 * 模板列表
 * Class manage
 * @package module\article
 */
class manage extends hdSite {
	//选择模板
	public function doSiteTemplate() {
		$data = api( 'template' )->get( q( 'get.type' ) );
		View::with( 'data', $data )->make( $this->template . '/manage/template.php' );
	}

	//站点管理
	public function doSiteSite() {
		$data = Db::table( 'web' )->where( 'siteid', '=', v( 'site.siteid' ) )->get();
		foreach ( $data as $k => $v ) {
			$data[ $k ]['site_info'] = json_decode( $v['site_info'], TRUE );
			$data[ $k ]['url']       = '?a=article/entry/home&webid=' . $v['id'];
		}
		View::with( 'data', $data )->make( $this->template . '/manage/site.php' );
	}

	//添加站点
	public function doSiteSitePost() {
		if ( IS_POST ) {
			$data = json_decode( $_POST['data'], TRUE );
			//添加关键词处理
			$rule['name']       = $data['title'];
			$rule['module']     = 'cover';
			$rule['rank']       = 0;
			$rule['status']     = $data['status'];
			$rule['rid']        = empty( $data['rid'] ) ? 0 : $data['rid'];
			$keyword['id']      = empty( $data['keyword_id'] ) ? 0 : $data['keyword_id'];
			$keyword['content'] = $data['keyword'];
			$keyword['type']    = 1;
			$keyword['rank']    = 0;
			$keyword['status']  = 1;
			$rule['keyword'][]  = $keyword;
			$rid                = api( 'rule' )->save( $rule );
			$rid || message( '添加回复关键词数据错误', 'back', 'error' );
			//添加微站
			$web['id']           = empty( $data['web_id'] ) ? 0 : $data['web_id'];
			$web['siteid']       = v( "site.siteid" );
			$web['template_tid'] = $data['template_tid'];
			$web['title']        = $data['title'];
			$web['site_info']    = $_POST['data'];
			$web['status']       = $data['status'];
			$web['domain']       = isset( $data['domain'] ) ? $data['domain'] : '';
			$web_id              = Db::table( 'web' )->replaceGetId( $web );
			$web_id || message( '添加微站数据错误', 'back', 'error' );
			//添加封面回复
			$cover['id']          = empty( $data['reply_cover_id'] ) ? 0 : $data['reply_cover_id'];
			$cover['siteid']      = v( "site.siteid" );
			$cover['web_id']      = $web_id;
			$cover['rid']         = $rid;
			$cover['module']      = 'site';
			$cover['do']          = '';
			$cover['title']       = $data['title'];
			$cover['description'] = $data['description'];
			$cover['thumb']       = $data['thumb'];
			$cover['url']         = '?a=article/entry/home&siteid=' . v( "site.siteid" ) . '&web_id=' . $web_id;
			$id                   = api( 'rule' )->saveReplyCover( $cover );
			$id || message( '添加封面回复数据错误', 'back', 'error' );
			message( '保存站点数据成功', site_url( 'site' ), 'success' );
		}
		if ( $webid = q( 'get.webid' ) ) {
			//编辑数据时
			$web                     = Db::table( 'web' )->where( 'id', $webid )->first();
			$field                   = json_decode( $web['site_info'], TRUE );
			$reply_cover             = Db::table( 'reply_cover' )->where( 'web_id', $web['id'] )->first();
			$field['rid']            = $reply_cover['rid'];
			$field['web_id']         = $web['id'];
			$field['keyword_id']     = Db::table( 'rule_keyword' )->where( 'rid', $reply_cover['rid'] )->pluck( 'id' );
			$field['reply_cover_id'] = $reply_cover['id'];
		}
		View::with( 'field', isset( $field ) ? json_encode( $field ) : '' );
		View::make( $this->template . '/manage/sitePost.php' );
	}

	//选择模板
	public function doSiteLoadTpl() {
		$data = api( 'template' )->get();
		View::with( 'data', $data )->make( $this->template . '/manage/loadTpl.php' );
	}
}