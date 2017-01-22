<?php namespace module\article\controller;
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace module\article\controller;


use module\HdController;
use system\model\ReplyCover;
use system\model\Web;

class Site extends HdController {
	//站点管理
	public function lists() {
		$data = Db::table( 'web' )->where( 'siteid', SITEID )->get() ?: [ ];
		foreach ( $data as $k => $v ) {
			$data[ $k ]['site_info'] = json_decode( $v['site_info'], true );
			$data[ $k ]['url']       = '?m=article&action=controller/entry/home&siteid=' . SITEID . '&webid=' . $v['id'];
		}

		return View::with( 'data', $data )->make( $this->template . '/site_lists.html' );
	}

	//设置默认站点
	public function setDefault() {
		$webid = Request::get( 'webid' );
		Web::where( 'siteid', siteid() )->update( [ 'is_default' => 0 ] );
		Web::where( 'siteid', siteid() )->where( 'id', $webid )->update( [ 'is_default' => 1 ] );
		message( '默认站点设置成功', 'back', 'success' );
	}

	//删除站点
	public function del() {
		\Web::del( Request::get( 'webid' ) );
		message( '站点删除成功', '', 'success' );
	}

	//添加站点
	public function post() {
		$id    = Request::get( 'webid' );
		$model = $id ? Web::find( $id ) : new Web();
		if ( IS_POST ) {
			$data                   = json_decode( $_POST['data'], true );
			$model['title']         = $data['title'];
			$model['status']        = $data['status'];
			$model['thumb']         = $data['thumb'];
			$model['template_name'] = $data['template_name'];
			$model['site_info']     = Request::post( 'data' );
			$model->save();
			//添加回复规则
			$rule             = [ ];
			$rule['rid']      = Db::table( 'reply_cover' )->where( 'web_id', $model['id'] )->pluck( 'rid' );
			$rule['module']   = 'cover';
			$rule['name']     = 'article:' . $model['id'];
			$rule['keywords'] = [ [ 'content' => $data['keyword'], ] ];
			$rid              = \Wx::rule( $rule );
			//添加封面回复
			$replyCover = new ReplyCover();
			$replyCover->where( 'rid', $rid )->delete();
			$replyCover['web_id']      = $model['id'];
			$replyCover['rid']         = $rid;
			$replyCover['title']       = $data['title'];
			$replyCover['description'] = $data['description'];
			$replyCover['thumb']       = $data['thumb'];
			$replyCover['module']      = 'article';
			$replyCover['url']         = '?m=article&action=entry/home&webid=' . $model['id'];
			$replyCover->save();
			message( '保存站点数据成功', url( 'site.lists' ), 'success' );
		}
		if ( $id ) {
			//编辑数据时
			$field       = json_decode( $model['site_info'], true );
			$field['id'] = $model['id'];
		}
		View::with( 'field', isset( $field ) ? json_encode( $field, JSON_UNESCAPED_UNICODE ) : '' );

		return View::make( $this->template . '/site_post.html' );
	}
}