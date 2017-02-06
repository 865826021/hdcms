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
/**
 * 前台模块管理
 * Class App
 * @package addons\store\controller
 */
class App extends Admin {
	//模块列表
	public function moduleLists() {
		$apps = Db::table( 'store_app' )->where( 'uid', v( 'member.info.uid' ) )->get();
		View::with( [ 'apps' => $apps ] );

		return view( $this->template . '/app.modulelists.html' );
	}

	//上架或下架模块
	public function changeModuleRacking() {
		Db::table( 'store_app' )->where( 'uid', v( 'member.info.uid' ) )->where( 'id', $_GET['id'] )->update( [ 'racking' => $_GET['racking'] ] );
		message( '模块状态修改成功', url( 'app.moduleLists' ) );
	}

	//添加插件或模块
	public function addModule() {
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			$data            = json_decode( Request::post( 'data' ), true );
			$data['type']    = 'module';
			$data['package'] = Request::post( 'data' );
			$model           = $id ? StoreApp::find( $id ) : new StoreApp();
			$model->save( $data );
			message( '模块信息添加成功', url( 'app.moduleLists' ), 'success' );
		}
		$field = "{detail: ''}";
		if ( $id ) {
			$field = Db::table( 'store_app' )->where( 'id', $id )->pluck( 'package' );
		}
		View::with( 'field', $field );

		return view( $this->template . '/app.addmodule.html' );
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
	 * 删除模块并删除应用的所有压缩包
	 */
	public function delModule() {
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
		message( '应用删除成功', url( 'app.moduleLists' ) );
	}

	//模块的压缩包管理
	public function moduleZips() {
		$this->has( Request::get( 'id' ) );
		$app  = Db::table( 'store_app' )->find( Request::get( 'id' ) );
		$zips = Db::table( 'store_zip' )->where( 'appid', Request::get( 'id' ) )->get();
		View::with( [ 'app' => $app, 'zips' => $zips ] );

		return view( $this->template . '/app.modulezips.html' );
	}

	//添加模块压缩包
	public function addModuleZip() {
		$file = Request::post( 'file' );
		$app  = Db::table( 'store_app' )->where( 'uid', v( 'member.info.uid' ) )->where( 'id', Request::get( 'id' ) )->first();
		if ( empty( $app ) ) {
			\Dir::delFile( $file );
			message( '模块不存在不允许添加压缩包', '', 'error' );
		}
		//解压缩
		Zip::PclZip( $file );
		Zip::extract( 'temp' );
		//添加表记录
		$package = json_decode( file_get_contents( "temp/{$app['name']}/package.json" ), true );
		if ( empty( $package ) ) {
			\Dir::delFile( $file );
			message( '压缩包中的package.json数据格式解析失败', '', 'error' );
		}
		if ( Db::table( 'store_zip' )->where( 'appid', $app['id'] )->where( 'version', $package['version'] )->first() ) {
			\Dir::delFile( $file );
			message( '已经存在相同版本的模块', '', 'error' );
		}
		$data['appid']      = $app['id'];
		$data['version']    = $package['version'];
		$data['file']       = "package/" . basename( $file );
		$data['createtime'] = time();
		$data['racking']    = 1;
		Db::table( 'store_zip' )->insert( $data );
		//移动压缩包到指定目录
		\Dir::create( 'package' );
		\Dir::moveFile( Request::post( 'file' ), "package" );
		message( '上传成功', '', 'success' );
	}

	//删除模块压缩包
	public function delModuleZip() {
		$zip = Db::table( 'store_zip' )->find( Request::get( 'id' ) );
		$this->has( $zip['appid'] );
		\Dir::delFile( $zip['file'] );
		Db::table( 'store_zip' )->where( 'id', Request::get( 'id' ) )->delete();
		message( '压缩包删除成功', url( 'app.moduleZips', [ 'id' => $zip['appid'] ] ), 'success' );
	}

	//检查当前模块或模板是否属于当前用户
	public function has( $id ) {
		$res = Db::table( 'store_app' )->where( 'uid', v( 'member.info.uid' ) )->where( 'id', $id )->get();
		if ( empty( $res ) ) {
			message( '应用不存在,无法进行操作', '', 'error' );
		}

		return true;
	}

	//更改模块压缩包状态
	public function changeModuleZipRacking() {
		$zip = Db::table( 'store_zip' )->find( Request::get( 'id' ) );
		$this->has( $zip['appid'] );
		Db::table( 'store_zip' )->where( 'id', $_GET['id'] )->update( [ 'racking' => $_GET['racking'] ] );
		message( '压缩包状态修改成功', url( 'app.moduleZips', [ 'id' => $zip['appid'] ] ) );
	}
}