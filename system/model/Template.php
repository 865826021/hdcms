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
 * 模板
 * Class Template
 * @package system\model
 * @author 向军
 */
class Template extends Common {
	protected $table = 'template';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[
			'name',
			'regexp:/^[a-z]+$/',
			'模板标识符,对应模板文件夹的名称, 系统按照此标识符查找模板定义, 只能由字母组成',
			self::MUST_VALIDATE,
			self::MODEL_INSERT
		],
		[ 'title', 'maxlen:10', '模板名称不能超过10个字符', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'industry', 'required', '模板类型不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'resume', 'required', '模板简述不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'author', 'required', '作者不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'detail', 'required', '详细介绍不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'url', 'required', '发布url不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'author', 'required', '作者不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'thumb', 'required', '模板缩略图不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'preview', 'required', '模板封面图片不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
		[ 'position', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ]
	];
}