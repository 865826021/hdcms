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

use addons\store\model\StoreTemplate;
use houdunwang\request\Request;

/**
 * 模板处理
 * Class Template
 * @package addons\store\controller
 */
class Template extends Admin {
	//模板列表
	public function lists() {
		$apps = StoreTemplate::where( 'uid', v( 'member.info.uid' ) )->get();
		View::with( [ 'apps' => $apps ] );

		return view( $this->template . '/template/lists.html' );
	}

	//上架或下架模板
	public function changeRacking() {
		StoreTemplate::where( 'uid', v( 'member.info.uid' ) )->where( 'id', $_GET['id'] )->update( [ 'racking' => $_GET['racking'] ] );
		message( '模板状态修改成功', url( 'template.lists' ) );
	}

	//添加模板
	public function post() {
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			//发表新模板
			$data = json_decode( Request::post( 'data' ), true );
			if ( empty( $id ) && StoreTemplate::where( 'name', $data['name'] )->first() ) {
				message( '模板已经存在,不允许发布同名模板', '', 'error' );
			}
			$data['package'] = Request::post( 'data' );
			$model           = $id ? StoreTemplate::find( $id ) : new StoreTemplate();
			$model->save( $data );
			message( '模板发布成功', url( 'template.lists' ), 'success' );
		}
		$field = "{detail: ''}";
		if ( $id ) {
			$field = StoreTemplate::where( 'id', $id )->pluck( 'package' );
		}
		View::with( 'field', $field );

		return view( $this->template . '/template/post.html' );
	}

	/**
	 * 上传的压缩包解压获取配置项
	 */
	public function unpack() {
		//创建上传目录
		Dir::create( "zips/template/images" );
		$file = Db::table( 'attachment' )->where( 'path', Request::post( 'file' ) )->first();
		Zip::PclZip( $file['path'] );//设置压缩文件名
		Zip::extract();
		Zip::extract( "temp/template" );//解压缩到hd目录
		$name = preg_replace( '/\.zip/i', '', $file['name'] );
		//解析配置文件
		$dir    = "temp/template/$name";
		$config = json_decode( file_get_contents( $dir . '/package.json' ), true );
		if ( empty( $config ) ) {
			message( '配置文件解析失败,可能是格式错误', '', 'error' );
		}
		//复制预览图片
		$appPreview = "zips/template/images/{$name}_" . $config['preview'];
		copy( $dir . '/' . $config['preview'], $appPreview );
		$config['app_preview'] = $appPreview;
		\Dir::del( $dir );
		message( $config, '', 'success' );
	}

	/**
	 * 删除模板并删除应用的所有压缩包
	 */
	public function del() {
		$model = StoreTemplate::where( 'uid', v( 'member.info.uid' ) )->find( Request::get( 'id' ) );
		if ( ! $model ) {
			message( '模板不存在', '', 'error' );
		}
		\Dir::delFile( $model['file'] );
		$model->destory();
		message( '模板删除成功', url( 'template.lists' ) );
	}

}