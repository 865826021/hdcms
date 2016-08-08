<?php namespace web\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
use system\model\ArticleCategory;
use system\model\User;

/**
 * 文章管理
 * Class Article
 * @package web\system\controller
 * @author 向军
 */
class Article {

	public function __construct() {
		$this->user = new User();
		if ( ! $this->user->isSuperUser() ) {
			message( '只有系统管理员可以执行操作', 'back', 'error' );
		}
	}

	//分类列表
	public function lists() {
		$Model = new ArticleCategory();
		$data  = $Model->get();
		View::with( 'data', $data );
		View::make();
	}

	//添加分类
	public function categoryPost() {
		$Model = new ArticleCategory();
		$id    = q( 'get.id' );
		if ( IS_POST ) {
			$action = $id ? 'save' : 'add';
			if ( $Model->$action() ) {
				message( '栏目保存成功', 'lists', 'success' );
			}
			message( $Model->getError(), 'back', 'error' );
		}
		$field = $Model->find( $id );
		View::with( 'field', $field );
		View::make();
	}

	//删除栏目
	public function delCategory() {
		$Model = new ArticleCategory();
		$Model->remove( q( 'get.id' ) );
		message( '文章删除成功', 'back', 'success' );
	}

	//新闻列表
	public function articleLists() {
		$Model = new \system\model\Article();
		$data  = $Model->lists();
		View::with( 'data', $data );
		View::make();
	}

	//文章管理
	public function articlePost() {
		$Category = new ArticleCategory();
		$Model    = new \system\model\Article();
		$id       = q( 'get.id' );
		if ( IS_POST ) {
			$action = $id ? 'save' : 'add';
			if ( $Model->$action() ) {
				message( '文章保存成功', 'articleLists', 'success' );
			}
			message( $Model->getError(), 'back', 'error' );
		}
		$field = $Model->find( $id );
		View::with( 'field', $field );
		View::with( 'category', $Category->get() );
		View::make();
	}

	//删除文章
	public function delArticle() {
		$Model = new \system\model\Article();
		$Model->where( 'id', q( 'get.id' ) )->delete();
		message( '文章删除成功', 'back', 'success' );
	}
}










