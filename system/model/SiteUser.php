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
 * 站点角色管理
 * Class SiteUser
 * @package system\model
 */
class SiteUser extends Common {
	protected $table = 'site_user';
	protected $validate = [
		[ 'uid', 'required', '用户编号不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'siteid', 'required', '网站编号不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'role', 'required', '角色类型role不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[
			'role',
			'validateRole',
			'角色类型为角色类型：owner(所有者),manage(管理员),operate(操作员)其中之一',
			self::MUST_VALIDATE,
			self::MODEL_INSERT
		],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
	];

	protected function validateRole( $field, $value, $params, $data ) {
		return in_array( $value, [ 'owner', 'manage', 'operate' ] ) ? true : false;
	}
}