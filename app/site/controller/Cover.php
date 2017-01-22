<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace app\site\controller;

use system\model\ReplyCover;

/**
 * 模块封面回复管理
 * Class Cover
 * @package app\site\controller
 */
class Cover {
	public function __construct() {
		auth();
	}

	/**
	 * 扩展模块封面回复设置
	 * @return mixed
	 */
	public function post() {
		//获取模块封面回复动作信息
		$module = Db::table( 'modules_bindings' )->where( 'bid', Request::get( 'bid' ) )->first();
		//回复的url会记录到reply_cover数据表中用于判断当前回复是否已经设置了
		$url = '?m=' . v( 'module.name' ) . '&action=system/cover/' . $module['do'];
		if ( IS_POST ) {
			$data             = json_decode( $_POST['keyword'], true );
			$cover            = Db::table( 'reply_cover' )->where( 'module', v( 'module.name' ) )->where( 'url', $url )->first();
			$data['name']     = v( 'module.name' ) . ':' . $data['name'];
			$data['rid']      = $cover['rid'];
			$data['module']   = 'cover';
			$data['rank']     = $data['istop'] == 1 ? 255 : min( 255, intval( $data['rank'] ) );
			$data['keywords'] = $data['keyword'];
			$rid              = \Wx::rule( $data );
			//添加封面回复
			$model                = $cover ? ReplyCover::find( $cover['id'] ) : new ReplyCover();
			$model['url']         = $module['do'];
			$model['rid']         = $rid;
			$model['title']       = $_POST['title'];
			$model['description'] = $_POST['description'];
			$model['thumb']       = $_POST['thumb'];
			$model['url']         = $_POST['url'];
			$model['module']      = v( 'module.name' );
			$model->save();
			message( '功能封面更新成功', 'back', 'success' );
		}
		$field = Db::table( 'reply_cover' )->where( 'siteid', SITEID )->where( 'module', v( 'module.name' ) )->where( 'url', $url )->first();
		//获取关键词回复
		if ( $field ) {
			$data            = Db::table( 'rule' )->where( 'rid', $field['rid'] )->first();
			$data['keyword'] = Db::table( 'rule_keyword' )->orderBy( 'id', 'asc' )->where( 'rid', $field['rid'] )->get();
			View::with( 'rule', $data );
		}
		$field['url']  = $url;
		$field['name'] = $module['title'];

		return view()->with( 'field', $field );
	}
}