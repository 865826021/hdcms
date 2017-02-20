<?php namespace addons\store\model;

use houdunwang\model\Model;

/**
 * 模块/模板基本信息
 * Class StoreApp
 * @package addons\store\model
 */
class StoreHdcms extends Model {
	protected $table = 'store_hdcms';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'file', 'required', '压缩包文件不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'logs', 'required', '更新日志不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'version', 'required', '版本号不能为空', self::EMPTY_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		//发布时间
		[ 'build', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}