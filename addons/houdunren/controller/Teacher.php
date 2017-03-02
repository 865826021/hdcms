<?php namespace addons\houdunren\controller;

use houdunwang\request\Request;
use module\HdController;
use addons\houdunren\model\Teacher as TeacherModel;

/**
 * 讲师管理
 * Class Teacher
 * @package addons\houdunren\controller
 */
class Teacher extends HdController {

	public function __construct() {
		parent::__construct();
		auth();
	}

	//列表
	public function lists() {
		$model = TeacherModel::get();
		View::with( 'data', $model );

		return view( $this->template . '/teacher/lists.html' );
	}

	//添加
	public function post() {
		$teacher_id = Request::get( 'teacher_id' );
		if ( IS_POST ) {
			$model = $teacher_id ? TeacherModel::find( $teacher_id ) : new TeacherModel();
			$model->save( Request::post() );
			message( '保存成功', url( 'teacher.lists' ) );
		}
		if ( $teacher_id ) {
			View::with( 'field', TeacherModel::find( $teacher_id ) );
		}

		return view( $this->template . '/teacher/post.html' );
	}

	//删除
	public function del() {
		$teacher_id = Request::get( 'teacher_id' );
		$model      = TeacherModel::find( $teacher_id );
		if ( $model ) {
			$model->destory();
		}
		message( '删除成功' );
	}
}