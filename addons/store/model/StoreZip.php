<?php namespace addons\store\model;

use houdunwang\model\Model;

/**
 * 模块/模板压缩包
 * Class StoreZip
 * @package addons\store\model
 */
class StoreZip extends Model {
	protected $table = 'store_zip';
	protected $allowFill = [ '*' ];
	protected $validate = [
	];
	protected $auto = [
		[ 'racking', 1, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}