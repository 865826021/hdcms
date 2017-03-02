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
use module\HdController;

class Lesson extends HdController {

	public function __construct() {
		parent::__construct();
		auth();
	}

	//课程列表
	public function lists() {
		return view( $this->template . '/lesson/lists.html' );
	}

	//发布课程
	public function post() {
		//获取讲师
		$teacher = Teacher::get();
		//标签
		$tag = Tag::get();
		View::with(['teacher'=>$teacher,'tag'=>$tag]);
//		p($tag->toArray());
		return view( $this->template . '/lesson/post.html' );
	}

}