<?php namespace system\model;

/**
 * 管理员组
 * Class UserGroup
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class UserGroup extends Common {
	protected $table = 'user_group';
	protected $validate
	                 = [
			[ 'name', 'required', '用户组称不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'maxsite', 'regexp:/^[1-9]\d*$/', '站点数量不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'daylimit', 'regexp:/^[1-9]\d*$/', '有效期限不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		];
	protected $auto
	                 = [
			[ 'package', 'serialize', 'function', self::MUST_AUTO, self::MODEL_BOTH ]
		];


}