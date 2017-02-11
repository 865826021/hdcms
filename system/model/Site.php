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
 * 站点模型
 * Class Site
 * @package system\model
 */
class Site extends Common {
	protected $table = 'site';
	protected $validate = [
		[ 'name', 'required', '站点名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'name', 'unique', '站点名称已经存在', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'description', 'required', '网站描述不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'weid', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
		[ 'allfilesize', 200, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
		[ 'description', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'ucenter_template', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}