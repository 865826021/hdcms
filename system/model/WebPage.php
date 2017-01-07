<?php namespace system\model;

/**
 * Class WebPage
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class WebPage extends Common {
	protected $table = 'web_page';
	protected $allowFill = [ '*' ];
	protected $validate
		= [
			[ 'title', 'required', '标题不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'type', 'required', '页面类型不能为空,1 快捷导航 2专题页面 3 会员中心', self::MUST_VALIDATE, self::MODEL_INSERT ],
		];
	protected $auto
		= [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'params', 'json_encode', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'html', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'status', 'intval', 'intval', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'web_id', 0, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
		];
}