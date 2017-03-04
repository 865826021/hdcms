<?php namespace addons\houdunren\controller;

/**
 * 课程管理
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author 向军
 * @url http://open.hdcms.com
 */
use addons\houdunren\model\Tag;
use addons\houdunren\model\Teacher;
use addons\houdunren\model\Lesson as LessonModel;
use houdunwang\request\Request;
use module\HdController;

class Lesson extends HdController {

	public function __construct() {
		parent::__construct();
		auth();
	}

	//课程列表
	public function lists() {
		$data = Db::table( 'houdunren_lesson' )->join( 'houdunren_teacher', 'houdunren_lesson.teacher_id', '=', 'houdunren_teacher.teacher_id' )->get();
		View::with( 'data', $data );

		return view( $this->template . '/lesson/lists.html' );
	}

	//发布课程
	public function post() {
		$lession_id = Request::get( 'lesson_id' );
		if ( IS_POST ) {
			$post     = json_decode( Request::post( 'field' ), true );
			$model    = $lession_id ? LessonModel::find( $lession_id ) : new LessonModel();
			$insertId = $model->save( $post );
			if ( ! $lession_id ) {
				$lession_id = $insertId;
			}
			//添加视频
			Db::table( 'houdunren_video' )->where( 'lesson_id', $lession_id )->delete();
			foreach ( $post['videos'] as $v ) {
				if ( ! empty( $v['video_title'] ) && ! empty( $v['video_path'] ) && ! empty( $v['video_duration'] ) && ! empty( $v['video_click'] ) ) {
					$v['lesson_id'] = $lession_id;
					$v['siteid']    = SITEID;
					Db::table( 'houdunren_video' )->insert( $v );
				}
			}
			//添加标签
			Db::table( 'houdunren_lesson_tag' )->where( 'lesson_id', $lession_id )->delete();
			foreach ( $post['tag'] as $v ) {
				$v['lesson_id'] = $lession_id;
				$v['siteid']    = SITEID;
				Db::table( 'houdunren_lesson_tag' )->insert( $v );
			}
			message( '课程保存成功' );
		}
		$data = [ 'lesson_content' => '', 'videos' => [ ] ];
		if ( $lession_id ) {
			$data           = $data = Db::table( 'houdunren_lesson' )->where( 'siteid', SITEID )->where( 'lesson_id', $lession_id )->first();
			$data['videos'] = Db::table( 'houdunren_video' )->where( 'siteid', SITEID )->where( 'lesson_id', $lession_id )->get();
		}
		//获取讲师
		$teacher         = Db::table( 'houdunren_teacher' )->where( 'siteid', SITEID )->get();
		$tag             = Db::table( 'houdunren_tag' )
		                     ->field( 'houdunren_tag.tag_id,houdunren_tag.tag_name,houdunren_lesson_tag.tag_id as lesson_tag_tid' )
		                     ->where( 'houdunren_tag.siteid', SITEID )
		                     ->leftJoin( 'houdunren_lesson_tag', 'houdunren_tag.tag_id', '=', 'houdunren_lesson_tag.tag_id' )
		                     ->groupBy( 'houdunren_tag.tag_id' )->get();
		$data['tag']     = $tag;
		$data['teacher'] = $teacher;

		View::with( 'data', json_encode( $data, JSON_UNESCAPED_UNICODE ) );

		return view( $this->template . '/lesson/post.html' );
	}

}