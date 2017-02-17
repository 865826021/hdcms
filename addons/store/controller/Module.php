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

use addons\store\model\StoreModule;
use addons\store\model\StoreZip;
use houdunwang\request\Request;

/**
 * 模块管理
 * Class App
 * @package addons\store\controller
 */
class Module extends Admin {
	//模块列表
	public function lists() {
		$apps = StoreModule::where( 'uid', v( 'member.info.uid' ) )->get();
		View::with( [ 'apps' => $apps ] );

		return view( $this->template . '/module/lists.html' );
	}

	//上架或下架模块
	public function changeModuleRacking() {
		StoreModule::where( 'uid', v( 'member.info.uid' ) )->where( 'id', $_GET['id'] )->update( [ 'racking' => $_GET['racking'] ] );
		message( '模块状态修改成功', url( 'module.lists' ) );
	}

	//添加模块
	public function post() {
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			//发表新模块
			$data = json_decode( Request::post( 'data' ), true );
			if ( empty( $id ) && StoreModule::where( 'name', $data['name'] )->first() ) {
				message( '模块已经存在,不允许发布同名模块', '', 'error' );
			}
			$data['package'] = Request::post( 'data' );
			$model           = $id ? StoreModule::find( $id ) : new StoreModule();
			$res             = $model->save( $data );
			//保存模块压缩包
			$model          = new StoreZip();
			$model['appid'] = $id ?: $res;
			$model->save( $data );
			message( '模块发布成功', url( 'module.lists' ), 'success' );
		}
		$field = "{detail: ''}";
		if ( $id ) {
			$field = StoreModule::where( 'id', $id )->pluck( 'package' );
		}
		View::with( 'field', $field );

		return view( $this->template . '/module/post.html' );
	}

	/**
	 * 上传的压缩包解压获取配置项
	 */
	public function unpack() {
		//创建上传目录
		Dir::create( "zips/module/images" );
		$file = Db::table( 'attachment' )->where( 'path', Request::post( 'file' ) )->first();
		Zip::PclZip( $file['path'] );//设置压缩文件名
		Zip::extract();
		Zip::extract( "temp/module" );//解压缩到hd目录
		$name = preg_replace( '/\.zip/i', '', $file['name'] );
		//解析配置文件
		$dir    = "temp/module/$name";
		$config = json_decode( file_get_contents( $dir . '/package.json' ), true );
		if ( empty( $config ) ) {
			message( '配置文件解析失败,可能是格式错误', '', 'error' );
		}
		//复制预览图片
		$appPreview = "zips/module/images/{$name}_" . $config['preview'];
		copy( $dir . '/' . $config['preview'], $appPreview );
		$config['app_preview'] = $appPreview;
		\Dir::del( $dir );
		message( $config, '', 'success' );
	}

	//删除模块并删除应用的所有压缩包
	public function del() {
		$id  = Request::get( 'id' );
		$app = StoreModule::where( 'id', $id )->where( 'uid', v( 'member.info.uid' ) )->get();
		if ( ! $app ) {
			message( '应用不存在', '', 'error' );
		}
		//删除压缩包
		$zips =StoreZip::where( 'appid', $app['id'] )->get();
		foreach ( $zips as $z ) {
			\Dir::delFile( $z['file'] );
		}
		StoreZip::where( 'appid', $app['id'] )->delete();
		StoreModule::where( 'id', $id )->delete();
		message( '模块删除成功', url( 'module.lists' ) );
	}

	//模块压缩包管理
	public function zips() {
		$this->has( Request::get( 'id' ) );
		$module  = StoreModule::find( Request::get( 'id' ) );
		$zips = StoreZip::where( 'appid', Request::get( 'id' ) )->get();
		View::with( [ 'app' => $module, 'zips' => $zips ] );

		return view( $this->template . '/module/zips.html' );
	}

	//添加压缩包数据
	public function addZip() {
		$model          = new StoreZip();
		$model['appid'] = Request::post( 'id' );
		$model->save( Request::post( 'data' ) );
		message( '模块发布成功', url( 'module.lists' ), 'success' );
	}

	//删除模块压缩包
	public function delZip() {
		$model = StoreZip::find( Request::get( 'id' ) );
		$this->has( $model['appid'] );
		\Dir::delFile( $model['file'] );
		StoreZip::where( 'id', Request::get( 'id' ) )->delete();
		message( '压缩包删除成功', url( 'module.zips', [ 'id' => $model['appid'] ] ), 'success' );
	}

	//检查当前模块或模板是否属于当前用户
	public function has( $id ) {
		$res = StoreModule::where( 'uid', v( 'member.info.uid' ) )->where( 'id', $id )->get();
		if ( empty( $res ) ) {
			message( '应用不存在,无法进行操作', '', 'error' );
		}

		return true;
	}

	//更改模块压缩包状态
	public function changeZipRacking() {
		$zip = StoreZip::find( Request::get( 'id' ) );
		$this->has( $zip['appid'] );
		StoreZip::where( 'id', $_GET['id'] )->update( [ 'racking' => $_GET['racking'] ] );
		message( '压缩包状态修改成功', url( 'module.zips', [ 'id' => $zip['appid'] ] ) );
	}
}