<?php namespace addons\course\model;

use houdunwang\model\Model;

/**
 * 学生资料
 * Class Student
 * @package addons\store\model
 */
class CourseClass extends Model {
	protected $table = 'course_class';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'name', 'required', '班级名称不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'city', 'required', '开班城市不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'times', 'required', '开班时间不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'orderby', 'num:0,255', '排序只能为0~255间的数字', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		//发布时间
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'orderby', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}