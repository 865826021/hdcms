<?php namespace addons\store\model;

use houdunwang\model\Model;

/**
 * 应用购买记录
 * Class StoreBuy
 * @package addons\store\model
 */
class StoreBuy extends Model {
	protected $table = 'store_buy';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'uid', 'required', '用户编号不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'name', 'required', '模块标识不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		//发布时间
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}