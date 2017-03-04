<?php namespace addons\houdunren\model;

use houdunwang\model\Model;

/**
 * 讲师管理
 * Class Teacher
 * @package addons\houdunren\model
 */
class Teacher extends Model {
	protected $table = 'houdunren_teacher';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'teacher_name', 'required', '讲师名称不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}