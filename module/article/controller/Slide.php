<?php namespace module\article\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use module\HdController;
use system\model\Web;
use system\model\WebSlide;

/**
 * 幻灯片管理
 * Class Slide
 * @package module\article\controller
 */
class Slide extends HdController {
	//列表
	public function lists() {
		if ( IS_POST ) {
			//修改排序
			foreach ( Request::post( 'slide' ) as $id => $order ) {
				$model                 = WebSlide::find( $id );
				$model['displayorder'] = $order;
				$model->save();
			}
			message( '修改幻灯片成功', 'refresh', 'success' );
		}
		$data = WebSlide::where( 'web_id', Request::get( 'webid' ) )->get();
		View::with( 'data', $data );

		return View::make( $this->template . '/slide_lists.html' );
	}

	//添加&修改
	public function post() {
		$id    = Request::get( 'id' );
		$webid = Request::get( 'webid' );
		$model = $id ? WebSlide::find( $id ) : new WebSlide();
		if ( IS_POST ) {
			$model['web_id'] = $webid;
			foreach ( Request::post() as $k => $v ) {
				$model[ $k ] = $v;
			}
			$model->save();
			message( '幻灯片保存成功', url( 'slide.lists', [ 'webid' => $webid ] ), 'success' );
		}
		//官网列表
		$web = Web::find( $webid );
		View::with( [ 'web' => $web, 'field' => $model ] );

		return View::make( $this->template . '/slide_post.html' );
	}

}