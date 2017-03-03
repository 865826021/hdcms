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
 * 模块管理模型
 * Class Modules
 * @package system\model
 */
class Modules extends Common {
	protected $table = 'modules';
	protected $denyInsertFields = [ 'mid' ];
	protected $validate = [
		[
			'name',
			'regexp:/^[a-z]+$/',
			'模块标识符, 对应模块文件夹的名称, 系统按照此标识符查找模块定义, 只能由字母组成',
			self::MUST_VALIDATE,
			self::MODEL_INSERT
		],
		[ 'industry', 'required', '模块类型不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'title', 'maxlen:50', '模块名称不能超过50个字符', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'version', 'regexp:/^[\d\.]+$/', '版本号只能为数字', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'resume', 'required', '模块简述不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'detail', 'required', '详细介绍不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'author', 'required', '作者不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'url', 'required', '发布url不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'author', 'required', '作者不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'thumb', 'required', '模块缩略图不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'preview', 'required', '模块封面图片不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
		[ 'name', 'strtolower', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
		[ 'is_system', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
		[ 'subscribes', 'json_encode', 'function', self::NOT_EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'processors', 'json_encode', 'function', self::NOT_EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'setting', 'intval', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
		[ 'rule', 'intval', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
		[ 'permissions', 'json_encode', 'function', self::NOT_EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'locality', 1, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'build', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'middleware', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}