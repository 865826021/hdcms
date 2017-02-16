<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace addons\store\controller;

use addons\store\model\StoreHdcms;

/**
 * 系统核心包管理
 * Class Hdcms
 * @package addons\store\controller
 */
class Hdcms extends Admin {
	public function __construct() {
		parent::__construct();
		auth();
	}

	//软件包列表
	public function lists() {
		$data = StoreHdcms::get();
		View::with( [ 'data' => $data ] );

		return view( $this->template . '/hdcms/lists.html' );
	}

	//添加压缩包
	public function post() {
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			$data  = json_decode( $_POST['data'], true );
			$model = $id ? StoreHdcms::find( $id ) : new StoreHdcms();
			$model->save( $data );
			message( '发布成功,用户将收到更新通知', url( 'hdcms.lists' ), 'success' );
		}
		$field = "{type:'upgrade',logs:'优化系统代码',explain:'请先将系统备份后再进行更新操作!'}";
		if ( $id ) {
			$field = json_encode( StoreHdcms::find( $id )->toArray(), JSON_UNESCAPED_UNICODE );
		}
		View::with( 'field', $field );

		return view( $this->template . '/hdcms/post.html' );
	}

	public function del() {
		$id    = Request::get( 'id' );
		$model = StoreHdcms::find( $id );
		if ( $model['update_site_num'] > 0 ) {
			message( '已经有站点下载了,所以不允许删除', '', 'error' );
		}
		if ( is_file( $model['file'] ) ) {
			@unlink( $model['file'] );
		}
		$model->destory();
		message( '删除成功', url( 'hdcms.lists' ), 'success' );
	}
}