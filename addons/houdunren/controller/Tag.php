<?php namespace addons\houdunren\controller;

use module\HdController;
use addons\houdunren\model\Tag as TagModel;

/**
 * 标签管理
 * Class Tag
 * @package addons\houdunren\controller
 */
class Tag extends HdController {

	public function __construct() {
		parent::__construct();
		auth();
	}

	//列表
	public function lists() {
		$model = TagModel::get();
		View::with( 'data', $model );

		return view( $this->template . '/tag/lists.html' );
	}

	//添加
	public function post() {
		$tag_id = Request::get( 'tag_id' );
		if ( IS_POST ) {
			$model = $tag_id ? TagModel::find( $tag_id ) : new TagModel();
			$model->save( Request::post() );
			message( '保存成功', url( 'tag.lists' ) );
		}
		if ( $tag_id ) {
			View::with( 'field', TagModel::find( $tag_id ) );
		}

		return view( $this->template . '/tag/post.html' );
	}

	//删除
	public function del() {
		$tag_id = Request::get( 'tag_id' );
		$model  = TagModel::find( $tag_id );
		if ( $model ) {
			$model->destory();
		}
		message( '删除成功' );
	}

}