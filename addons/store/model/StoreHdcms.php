<?php namespace addons\store\model;

use houdunwang\model\Model;

/**
 * 模块/模板基本信息
 * Class StoreApp
 * @package addons\store\model
 */
class StoreApp extends Model {
	protected $table = 'store_hdcms';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'file', 'required', '压缩包文件不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'logs', 'required', '更新日志不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}