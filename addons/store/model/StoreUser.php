<?php namespace addons\store\model;

use houdunwang\model\Model;

/**
 * 开发者用户管理
 * Class StoreUser
 * @package addons\store\model
 */
class StoreUser extends Model {
	protected $table = 'store_user';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'uid', 'required', 'uid不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}