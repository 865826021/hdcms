<?php namespace addons\houdunren\model;

use houdunwang\model\Model;

/**
 * 标签管理
 * Class Tag
 * @package addons\houdunren\model
 */
class Tag extends Model {
	protected $table = 'houdunren_tag';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'tag_name', 'required', '标签名称不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
	];
}