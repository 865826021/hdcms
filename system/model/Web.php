<?php namespace system\model;

/**
 * 微站
 * Class Web
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Web extends Common {
	protected $table = 'web';
	protected $denyInsertFields = [ 'id' ];
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'title', 'required', '站点名称不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'title', 'unique', '站点名称已经存在', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'template_tid', 'required', '请选择网站模板风格', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'domain', 'http', '域名格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'status', 'num:0,1', '网站状态只能为1或9', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'thumb', 'required', '封面不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'domain', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
	];

}