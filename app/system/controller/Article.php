<?php namespace app\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
use system\model\ArticleCategory;

/**
 * 文章管理
 * Class Article
 * @package web\system\controller
 * @author 向军
 */
class Article {

	public function __construct() {

	}

	//分类列表
	public function lists() {
		service( 'user' )->superUserAuth();
		$Model = new ArticleCategory();
		$data  = $Model->get();

		return view()->with( 'data', $data );
	}

	//添加分类
	public function categoryPost() {
		service( 'user' )->superUserAuth();
		$Model = new ArticleCategory();

		if ( IS_POST ) {
			$Model->id       = Request::post( 'id' );
			$Model->title    = Request::post( 'title' );
			$Model->orderby  = Request::post( 'orderby' );
			$Model->template = Request::post( 'template' );
			$Model->save();
			message( '栏目保存成功', 'lists', 'success' );
		}
		//查找模板
		$category = $Model->find( Request::get( 'id' ) ) ?: [ ];
		$template = glob( 'theme/article/*.html' );

		return view()->with( [ 'field' => $category, 'template' => $template ] );
	}

	//删除栏目
	public function delCategory() {
		service( 'user' )->superUserAuth();
		$Model = new ArticleCategory();
		$Model->remove( Request::get( 'id' ) );
		message( '栏目删除成功', 'back', 'success' );
	}

	//新闻列表
	public function articleLists() {
		service( 'user' )->superUserAuth();
		$Model = new \system\model\Article();
		$data  = $Model->lists();

		return view()->with( 'data', $data );
	}

	//文章管理
	public function articlePost() {
		service( 'user' )->superUserAuth();
		$Category = new ArticleCategory();
		$Model    = new \system\model\Article();
		$id       = q( 'get.id' );
		if ( IS_POST ) {
			$Model->save(Request::post());
			message( '文章保存成功', 'articleLists', 'success' );
		}
		//查找模板
		$template = glob( 'theme/article/*.html' );
		$field    = $Model->find( $id ) ?: [ ];

		return view()->with( [ 'field' => $field, 'category' => $Category->get(), 'template' => $template ] );
	}

	//删除文章
	public function delArticle() {
		service( 'user' )->superUserAuth();
		$Model = new \system\model\Article();
		$Model->where( 'id', q( 'get.id' ) )->delete();
		message( '文章删除成功', 'back', 'success' );
	}

	public function show() {
		$id       = q( 'get.id', 0, 'intval' );
		$data     = Db::table( 'article' )->find( $id );
		$category = Db::table( 'article_category' )->find( $data['cid'] );
		if ( ! empty( $data['url'] ) ) {
			//有链接时跳转
			go( $data['url'] );
		} else {
			$template = empty( $data['template'] ) ? ( empty( $category['template'] ) ? 'theme/article/article.html' : $category['template'] ) : $data['template'];
			//相关文章
			$relation = Db::table( 'article' )->limit( 10 )->where( 'cid', $data['cid'] )->get();
			View::with( 'relation', $relation );
			View::with( 'article', $data );
			View::with( 'category', $category );

			return view( $template );
		}
	}
}










