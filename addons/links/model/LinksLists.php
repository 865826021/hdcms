<?php namespace addons\links\model;

use houdunwang\model\Model;

/**
 * 学生资料
 * Class LinksLists
 * @package addons\store\model
 */
class LinksLists extends Model {
	protected $table = 'links_lists';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'name', 'required', '链接名称不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'url', 'required', '网址不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'orderby', 'num:0,255', '排序只能为0~255间的数字', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		//发布时间
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'orderby', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'status', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}