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
use module\HdController;
use module\article\model\Field as FieldModel;

/**
 * 模型字段管理
 * Class Field
 * @package module\article\controller
 */
class Field extends HdController {
	public function __construct() {
		parent::__construct();
		auth();
	}

	//字段列表
	public function lists() {
		$data = FieldModel::where( 'siteid', SITEID )->where( 'mid', Request::get( 'mid' ) )->get();
		View::with( 'data', $data );

		return view( $this->template . '/content/field_lists.html' );
	}

	//修改字段
	public function post() {
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			$model = $id ? FieldModel::find( $id ) : new FieldModel();
			//新增时添加表字段
			$model->createField( Request::post( 'options' ) );
			$data            = json_decode( Request::post( 'options' ), true );
			$data['mid']     = Request::get( 'mid' );
			$data['options'] = Request::post( 'options' );
			$model->save( $data );
			message( '字段保存成功', url( 'field.lists', [ 'mid' => Request::get( 'mid' ) ] ) );
		}
		View::with( 'field', $model ?: [ ] );

		if ( $id ) {
			$options = FieldModel::where( 'id', $id )->pluck( 'options' );
		} else {
			$options = "{
                    title: '',
                    name: '',
                    orderby: 0,
                    required: 0,
                    type: 'string',
                    field: 'varchar',
                    form: 'input',
                    length: 100
                }";
		}
		View::with( 'options', $options );

		return view( $this->template . '/content/field_post.html' );
	}

	//删除模型
	public function del() {
		$model = FieldModel::find( Request::get( 'id' ) );
		if ( $model->delField() ) {
			message( '字段删除成功', '', 'success' );
		}
		message( $model->getError(), '', 'error' );
	}
}