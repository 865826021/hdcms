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

use module\article\model\WebCategory;
use module\article\model\WebModel;
use module\HdController;
use system\model\Router;

/**
 * 栏目管理
 * Class Category
 * @package module\article\controller
 */
class Category extends HdController {
	public function __construct() {
		parent::__construct();
		auth();
	}

	//栏目列表
	public function lists() {
		$data = ( new WebCategory() )->getLevelCategory();
		View::with( 'data', $data );

		return view( $this->template . '/content/category_lists.html' );
	}

	//修改栏目
	public function post() {
		$cid = Request::get( 'cid' );
		if ( IS_POST ) {
			$model = $cid ? WebCategory::find( $cid ) : new WebCategory();
			$data  = json_decode( Request::post( 'data' ), true );
			$model->save( $data );
			message( '栏目保存成功', url( 'category.lists' ) );
		}
		$category = WebCategory::getLevelCategory( $cid );
		$model    = WebModel::getLists();
		if ( empty( $model ) ) {
			message( '请先添加模块后再进行操作', url( 'model.lists' ), 'info' );
		}
		if ( $cid ) {
			$field = WebCategory::find( $cid )->toArray();
		}
		View::with( [ 'category' => $category, 'model' => $model, 'field' => $field ] );

		return view( $this->template . '/content/category_post.html' );
	}

	//删除栏目
	public function del() {
		$cid = Request::get( 'cid' );
		//删除子栏目
		$child = \Arr::channelList( Db::table( 'web_category' )->get(), $cid );
		if ( $child ) {
			message( '请先删除子栏目', '', 'error' );
		}

		$model = WebCategory::find( $cid );
		if ( $model->delCategory() ) {
			message( '栏目删除成功', url( 'category.lists' ), 'success' );
		} else {
			message( $model->getError(), url( 'category.lists' ), 'error' );
		}
	}
}