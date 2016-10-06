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


use hdphp\model\Model;

/**
 * 管理员模型
 * Class User
 * @package system\model
 */
class User extends Model {
	protected $table            = 'user';
	protected $denyInsertFields = [ 'uid' ];
	protected $validate
	                            = [
			[ 'username', 'required', '用户名不能为空', self::EXIST_VALIDATE, self::MODEL_INSERT ],
			[ 'username', 'regexp:/^[a-z][\w@]+$/i', '用户名必须是字母,数字,下划线或 @ 符号,并且必须以字母开始', self::EXIST_VALIDATE, self::MODEL_INSERT ],
			[ 'username', 'unique', '用户名已经存在', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'groupid', 'required', '用户组不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
			[ 'password', 'required', '密码不能为空', self::NOT_EMPTY_VALIDATE, self::MODEL_INSERT ],
			[ 'password', 'minlen:6', '请输入不少于6位的密码', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'qq', 'regexp:/^\d+$/', '请输入正确的QQ号', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'qq', 'unique', 'QQ号已经被使用', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'email', 'email', '邮箱格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_INSERT ],
			[ 'email', 'unique', '邮箱已经被注册', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'regexp:/^\d{11}$/', '手机号格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'unique', '手机号已经被其他用户注册', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		];

	protected $auto
		= [
			[ 'groupid', 'autoGroupId', 'method', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'password', 'autoPassword', 'method', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'regtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'mobile_valid', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'email_valid', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'regip', 'clientIp', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'lasttime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'lastip', 'clientIp', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'starttime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'starttime', 'strtotime', 'function', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'endtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'endtime', 'strtotime', 'function', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'remark', '', 'string', self::MUST_AUTO, self::MODEL_INSERT ],
		];
	protected $filter
		= [
			[ 'password', self::EMPTY_FILTER, self::MODEL_BOTH ],
		];
	/**
	 * 删除用户时关联删除数据的表
	 * @var array
	 */
	protected $relationDeleteTable
		= [
			'user',//用户表
			'site_user',//站点管理员
			'user_permission',//用户管理权限
			'user_profile',//用户字段信息
		];

	/**
	 * 密码自动完成处理
	 *
	 * @param $val
	 * @param $data
	 *
	 * @return string
	 */
	protected function autoPassword( $val, &$data ) {
		$data['security'] = substr( md5( time() ), 0, 10 );

		return md5( $val . $data['security'] );
	}

	/**
	 * 删除用户
	 * @return bool
	 */
	public function remove() {
		foreach ( $this->relationDeleteTable as $t ) {
			Db::table( $t )->where( 'uid', $this->uid )->delete();
		}

		return TRUE;
	}

	/**
	 * 会员组表关联
	 * @return mixed
	 */
	public function userGroup() {
		return $this->belongsTo( 'system\model\UserGroup', 'groupid' );
	}
}