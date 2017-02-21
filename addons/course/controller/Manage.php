<?php namespace addons\course\controller;

/**
 * manage
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author 后盾团队
 * @url http://open.hdcms.com
 */
use addons\course\model\CourseClass;
use module\HdController;

class Manage extends HdController {

	public function __construct() {
		parent::__construct();
		auth();
	}

	//学生列表
	public function lists() {

		if ( IS_POST ) {
			foreach ( (array) $_POST['orderby'] as $id => $order ) {
				$model            = CourseClass::find( $id );
				$model['orderby'] = $order;
				$model->save();
			}
		}
		$data = CourseClass::paginate( 10 );
		View::with( 'data', $data );

		return view( $this->template . '/manage/lists.html' );
	}

	//保存学生资料
	public function post() {
		$id    = Request::get( 'id' );
		$model = $id ? CourseClass::find( $id ) : new CourseClass();
		if ( IS_POST ) {
			$model->save( Request::post() );
			message( '班级资料修改成功', 'lists', 'success' );
		}
		if ( $id ) {
			View::with( 'field', $model->toArray() );
		}

		return view( $this->template . '/manage/post.html' );
	}

	//删除班级
	public function del() {
		$model = CourseClass::find( Request::get( "id" ) );
		$model->destory();
		message( '开班资料删除成功', '', 'success' );
	}

}