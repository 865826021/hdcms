<?php namespace addons\salary\model;

use houdunwang\model\Model;

/**
 * 学生资料
 * Class Student
 * @package addons\store\model
 */
class Student extends Model {
	protected $table = 'salary_student';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'name', 'required', '学生姓名不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'graduation', 'required', '毕业时间不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'wage', 'required', '就业薪资不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'address', 'required', '工作地址不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'orderby', 'num:0,255', '排序只能为0~255间的数字', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		//发布时间
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'orderby', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'present', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}