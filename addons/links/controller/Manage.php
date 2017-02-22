<?php namespace addons\links\controller;

/**
 * 链接管理
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author 后盾人团队
 * @url http://open.hdcms.com
 */
use addons\links\model\LinksLists;
use module\HdController;

class Manage extends HdController {

	public function __construct() {
		parent::__construct();
		auth();
	}

	//链接列表
	public function lists() {
		if ( IS_POST ) {
			foreach ( (array) $_POST['orderby'] as $id => $order ) {
				$model            = LinksLists::find( $id );
				$model['orderby'] = $order;
				$model->save();
			}
		}
		$status = Request::get( 'status', 1 );
		$data   = LinksLists::where( 'status', $status )->paginate( 10 );
		View::with( 'data', $data );

		return view( $this->template . '/manage/lists.html' );
	}

	//保存链接资料
	public function post() {
		$id    = Request::get( 'id' );
		$model = $id ? LinksLists::find( $id ) : new LinksLists();
		if ( IS_POST ) {
			$model->save( Request::post() );
			message( '链接资料修改成功', 'lists', 'success' );
		}
		if ( $id ) {
			View::with( 'field', $model->toArray() );
		}

		return view( $this->template . '/manage/post.html' );
	}

	//删除链接
	public function del() {
		$model = LinksLists::find( Request::get( "id" ) );
		$model->destory();
		message( '链接资料删除成功', '', 'success' );
	}

}