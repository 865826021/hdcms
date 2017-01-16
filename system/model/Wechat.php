<?php namespace system\model;

/**
 * 公众号管理接口
 * Class Wechat
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Wechat extends Common {
	protected $table = 'site_wechat';
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ]
	];
}