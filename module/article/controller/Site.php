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

use module\article\model\Web;
use module\HdController;
use system\model\ReplyCover;

class Site extends HdController {
	//添加站点
	public function post() {
		auth();
		if ( IS_POST ) {
			$model                  = Web::where( 'siteid', SITEID )->first() ?: new Web();
			$data                   = json_decode( $_POST['data'], true );
			$model['title']         = $data['title'];
			$model['status']        = $data['status'];
			$model['thumb']         = $data['thumb'];
			$model['template_name'] = $data['template_name'];
			$model['site_info']     = Request::post( 'data' );
			$model->save();
			//添加回复规则
			$rule             = [ ];
			$rule['rid']      = $data['rid'];
			$rule['module']   = 'cover';
			$rule['name']     = 'article:site:' . SITEID;
			$rule['keywords'] = [ [ 'content' => $data['keyword'], ] ];
			$rid              = \Wx::rule( $rule );
			//添加封面回复
			$replyCover = new ReplyCover();
			$replyCover->where( 'rid', $rid )->delete();
			$replyCover['rid']         = $rid;
			$replyCover['title']       = $data['title'];
			$replyCover['description'] = $data['description'];
			$replyCover['thumb']       = $data['thumb'];
			$replyCover['module']      = 'article';
			$replyCover['url']         = '?m=article&action=entry/home&webid=' . $model['id'];
			$replyCover->save();
			message( '保存站点数据成功', __URL__, 'success' );
		}
		$model = Web::where( 'siteid', SITEID )->first();
		if ( $model ) {
			//编辑数据时
			$field       = json_decode( $model['site_info'], true );
			$field['id'] = $model['id'];
			/**
			 * 微信回复规则编号
			 * 用于检测关键词是否存在及添加到rule表中使用
			 */
			$field['rid'] = Db::table( 'rule' )->where( 'name', "article:site:" . SITEID )->pluck( 'rid' );
		}
		View::with( 'field', $model ? json_encode( $field, JSON_UNESCAPED_UNICODE ) : '' );

		return View::make( $this->template . '/site_post.html' );
	}
}