<?php namespace system\model;

/**
 * 导航菜单管理
 * Class WebNav
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Navigate extends Common {
	protected $table = 'navigate';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'name', 'required', '导航名称不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		[ 'url', 'required', '链接不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		[ 'orderby', 'num:0,255', '排序只能为0~255之间的数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		[ 'entry', 'required', '导航类型不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'webid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'module', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'css', 'json_encode', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'category_cid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'position', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'position', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'orderby', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'icontype', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'entry', 'strtolower', 'function', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
	];

}