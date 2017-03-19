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
use module\article\model\WebContent;
use module\article\model\WebModel;
use module\HdController;
use system\model\ReplyCover;

/**
 * 文章管理
 * Class Content
 * @package module\article\controller
 */
class Content extends HdController {
	public function __construct() {
		parent::__construct();
		auth();
		$mid = Request::get( 'mid' );
		if ( empty( $mid ) ) {
			$mid = Db::table( 'web_model' )->where( 'siteid', SITEID )->pluck( 'mid' );
			go( __URL__ . "&mid={$mid}" );
		}
	}

	//文章列表
	public function lists() {
		if ( IS_POST ) {
			foreach ( $_POST['orderby'] as $aid => $order ) {
				$model            = WebContent::find( $aid );
				$model['orderby'] = $order;
				$model->save();
			}
		}
		$model = new WebContent();
		$table = $model->getTableName();
		$db    = $model->field( "*,{$table}.orderby" )->orderBy( "{$table}.orderby", "desc" )->orderBy( 'aid', 'DESC' )
		               ->join( 'web_category', "{$table}.cid", '=', 'web_category.cid' );
		if ( $cid = Request::get( 'cid' ) ) {
			$db->where( 'web_category.cid', $cid );
		}
		View::with( 'data', $db->paginate( 10 ) );
		View::with( 'category', WebCategory::getLevelCategory() );

		return view( $this->template . '/content/content_lists.html' );
	}

	//修改文章
	public function post() {
		$aid = Request::get( 'aid' );
		if ( IS_POST ) {
			$data        = Request::post();
			$model       = $aid ? WebContent::find( $aid ) : new WebContent();
			$data['mid'] = Request::get( 'mid' );
			$model->save( $data );
			$aid = $aid ?: $model['aid'];
			//添加回复规则
			if ( empty( $data['wechat_keyword'] ) ) {
				\Wx::removeRule( Request::post( 'wechat_rid' ) );
			} else {
				if ( ! empty( $data['wechat_keyword'] ) ) {
					\Wx::cover( [
						'keyword'     => $data['wechat_keyword'],
						'title'       => $data['title'],
						'description' => $data['description'],
						'thumb'       => $data['thumb'],
						'url'         => url( 'entry.content', [
							'aid' => $aid,
							'mid' => $_GET['mid'],
							'cid' => $data['category_cid']
						] )
					] );
				}
			}
			message( '文章保存成功', url( 'content.lists' ) );

		}
		//编辑时获取原数据
		//微信关键词信息
		$wechat = [];
		$field  = [];
		if ( $aid ) {
			$field = WebContent::find( $aid )->toArray();
			$url = url( 'entry.content', [
				'aid' => $field['aid'],
				'mid' => $_GET['mid'],
				'cid' => $field['category_cid']
			]);
			echo $url;
			$wechat['rid']            = Db::table( 'rule' )->where( 'name', 'article:content:' . $aid )->pluck( 'rid' );
			$wechat['wechat_keyword'] = Db::table( 'rule_keyword' )->where( 'rid', $wechat['rid'] )->pluck( 'content' );
		}
		//栏目列表
		$category = WebCategory::getLevelCategory( $aid ? $field['category_cid'] : '' );
		$extField = service( 'article.field.make', $field );
		View::with( 'extField', $extField );
		View::with( [ 'category' => $category, 'field' => $field, 'wechat' => $wechat ] );

		return view( $this->template . '/content/content_post.html' );
	}

	//删除文章
	public function del() {
		$model = WebContent::find( Request::get( 'aid' ) );
		if ( $model->destory() ) {
			message( '文章删除成功', url( 'content.lists' ), 'success' );
		} else {
			message( $model->getError(), url( 'content.lists' ), 'error' );
		}
	}
}