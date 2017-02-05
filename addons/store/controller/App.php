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

use addons\store\model\StoreApp;
use module\HdController;

/**
 * 前台模块管理
 * Class App
 * @package addons\store\controller
 */
class App extends HdController {
	public function __construct() {
		parent::__construct();
		\Member::isLogin();
		$this->assignAppTitle();
	}

	//分配标题名称
	protected function assignAppTitle() {
		$type   = Request::get( 'type' );
		$titles = [ 'module' => '模块', 'template' => '模板' ];
		View::with( [ 'app_title' => $titles[ $type ] ] );
	}

	//模块或插件列表
	public function lists() {
		$apps = Db::table( 'store_app' )->where( 'uid', v( 'member.info.uid' ) )->get();
		View::with( [ 'apps' => $apps ] );

		return view( $this->template . '/app.lists.html' );
	}

	/**
	 * 上架或下架应用
	 */
	public function changeRacking() {
		Db::table( 'store_app' )->where( 'uid', v( 'member.info.uid' ) )->where( 'id', $_GET['id'] )->update( [ 'racking' => $_GET['racking'] ] );
		message( '应用状态修改成功', url( 'app.lists', [ 'type' => $_GET['type'] ] ) );
	}

	//添加插件或模块
	public function add() {
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			$data            = json_decode( Request::post( 'data' ), true );
			$data['type']    = Request::post( 'type' );
			$data['package'] = Request::post( 'data' );
			$model           = $id ? StoreApp::find( $id ) : new StoreApp();
			$model->save( $data );
			message( '应用基本信息添加成功', url( 'app.lists', [ 'type' => $_GET['type'] ] ), 'success' );
		}
		$field = "{detail: ''}";
		if ( $id ) {
			$field = Db::table( 'store_app' )->where( 'id', $id )->pluck( 'package' );
		}
		View::with( 'field', $field );

		return view( $this->template . '/app.add.html' );
	}

	/**
	 * 初次添加模块时需要先上传package.json数据包
	 * 然后再上传zip压缩包
	 */
	public function unpack() {
		$file = Db::table( 'attachment' )->where( 'path', Request::post( 'file' ) )->first();
		ajax( json_decode( file_get_contents( $file['path'] ), true ) );
	}

	/**
	 * 删除应用并删除应用的所有压缩包
	 */
	public function del() {
		$id  = Request::get( 'id' );
		$app = Db::table( 'store_app' )->where( 'id', $id )->where( 'uid', v( 'member.info.uid' ) )->get();
		if ( ! $app ) {
			message( '应用不存在', '', 'error' );
		}
		//删除压缩包
		$zips = Db::table( 'store_zip' )->where( 'appid', $app['id'] )->get();
		foreach ( $zips as $z ) {
			\Dir::delFile( $z['file'] );
		}
		Db::table( 'store_zip' )->where( 'appid', $app['id'] )->delete();
		Db::table( 'store_app' )->where( 'id', $id )->delete();
		message( '应用删除成功', url( 'app.lists', [ 'type' => $_GET['type'] ] ) );
	}
}