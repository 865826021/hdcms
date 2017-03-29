<?php namespace system\model;

/**
 * 会员管理
 * Class Member
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Member extends Common {
	protected $table = 'member';
	protected $allowFill = [ '*' ];
	protected $filter = [
		[ 'password', self::EMPTY_FILTER, self::MODEL_BOTH ],
	];
	protected $validate = [
		[ 'password', 'required', '密码不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		[ 'password', 'minlen:5', '密码长度不能小于5位', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		[ 'email', 'email', '邮箱格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'email', 'unique', '邮箱已经被使用', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'mobile', 'unique', '手机号已经被使用', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'mobile', 'phone', '手机号格式错误', self::NOT_EMPTY_VALIDATE, self::MODEL_BOTH ],
		[ 'uid', 'checkUid', '当前用户不属于站点', self::EXIST_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::EMPTY_AUTO, self::MODEL_BOTH ],
		[ 'mobile', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'email', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'icon', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'credit1', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'credit2', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'credit3', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'credit4', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'credit5', 'intval', 'function', self::EXIST_AUTO, self::MODEL_BOTH ],
		[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'qq', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'nickname', '幸福小海豚', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'realname', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'telephone', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'vip', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'address', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'zipcode', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'alipay', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'msn', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'taobao', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'site', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'nationality', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'introduce', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'gender', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'graduateschool', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'height', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'weight', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'bloodtype', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'birthyear', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'birthmonth', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'birthday', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'resideprovince', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'residecity', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'residedist', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'access_token', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'group_id', 'getDefaultGroupId', 'method', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
	];

	protected function getDefaultGroupId() {
		return \Member::getDefaultGroup();
	}

	public function checkUid( $field, $value, $params, $data ) {
		return Db::table( $this->table )->where( 'uid', $value )->where( 'siteid', SITEID )->first() ? true : false;
	}

	public function group() {
		return $this->hasOne( MemberAuth::class, 'uid', 'uid' );
	}
}