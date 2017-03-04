<?php namespace addons\houdunren\model;

use houdunwang\model\Model;

/**
 * 课程管理
 * Class Lesson
 * @package addons\houdunren\model
 */
class Lesson extends Model {
	protected $table = 'houdunren_lesson';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'lesson_title', 'required', '标题不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}