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

class Site extends HdController {
	//站点管理
	public function lists() {
		$data = Db::table( 'web' )->where( 'siteid', SITEID )->get() ?: [ ];
		foreach ( $data as $k => $v ) {
			$data[ $k ]['site_info'] = json_decode( $v['site_info'], true );
			$data[ $k ]['url']       = '?a=entry/home&m=article&siteid=' . SITEID . '&webid=' . $v['id'];
		}

		return View::with( 'data', $data )->make( $this->template . '/site_lists.html' );
	}

	//添加站点
	public function post() {
		if ( IS_POST ) {
			$data              = json_decode( $_POST['data'], true );
			$data['site_info'] = $_POST['data'];
			$insertId          = $this->web->save( $data );
			$web['id']         = $this->webid ?: $insertId;
			//添加回复规则
			$rule             = [ ];
			$rule['rid']      = Db::table( 'reply_cover' )->where( 'web_id', $web['id'] )->pluck( 'rid' );
			$rule['module']   = 'cover';
			$rule['name']     = '微站:' . $data['title'];
			$rule['keywords'] = [
				[
					'content' => $data['keyword'],
				]
			];
			$rid              = service( 'WeChat' )->rule( $rule );
			//添加封面回复
			$replyCover = new ReplyCover();
			$replyCover->where( 'rid', $rid )->delete();
			$data['web_id'] = $web['id'];
			$data['rid']    = $rid;
			$data['module'] = 'article';
			$data['url']    = '?a=entry/home&m=article&t=web&siteid=' . SITEID . '&webid=' . $web['id'];
			$replyCover->save( $data );
			message( '保存站点数据成功', site_url( 'site' ), 'success' );
		}
		if ( $this->webid ) {
			//编辑数据时
			$web         = $this->web->find( $this->webid );
			$field       = json_decode( $web['site_info'], true );
			$field['id'] = $this->webid;
		}
		View::with( 'field', isset( $field ) ? json_encode( $field, JSON_UNESCAPED_UNICODE ) : '' );

		return View::make( $this->template . '/site_post.html' );
	}
}