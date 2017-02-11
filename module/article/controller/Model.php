<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace module\article\controller;


use houdunwang\request\Request;
use module\article\model\WebModel;
use module\HdController;

class Model extends HdController {
	public function __construct() {
		parent::__construct();
		auth();
	}

	//模块列表
	public function lists() {
		$data = WebModel::get();
		View::with( 'data', $data );

		return view( $this->template . '/content/model_lists.html' );
	}

	//修改模型
	public function post() {
		$model = Request::get( 'mid' ) ? WebModel::find( Request::get( 'mid' ) ) : new WebModel();
		if ( IS_POST ) {
			$model->save( Request::post() );
			//添加时创建表
			$model->createModelTable( Request::post( 'model_name' ) );
			message( '模型保存成功', url( 'model.lists' ) );
		}
		View::with( 'field', $model ?: [ ] );

		return view( $this->template . '/content/model_post.html' );
	}

	//删除模型
	public function del() {
		$mid = Request::get( 'mid' );
		if ( Db::table( 'web_category' )->where( 'mid', $mid )->get() ) {
			message( '请删除使用该模型的栏目后,再删除模型.', '', 'error' );
		}
		$model = WebModel::find( $mid );
		if ( $model->delModel() ) {
			message( '模型删除成功', url( 'model.lists' ), 'success' );
		}else{
			message( $model->getError(), url( 'model.lists' ), 'error' );
		}
	}
}