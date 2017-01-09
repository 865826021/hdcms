<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\model;

/**
 * 套餐模型
 * Class Package
 * @package system\model
 */
class Package extends Common {
	protected $table = "package";
	protected $validate = [
		[ 'name', 'required', '套餐名不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
	];

	protected $auto = [
		[ 'modules', 'json_encode', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'template', 'json_encode', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
	];
}