<?php namespace module\material\model;
use houdunwang\model\Model;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
class Material extends Model {
	protected $table = 'material';

	protected $auto = [
		[ 'data', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'file', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'media_id', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'url', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'siteid', SITEID, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'status', 1, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ]
	];
}