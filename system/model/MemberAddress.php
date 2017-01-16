<?php namespace system\model;

/**
 * 会员地址管理
 * Class MemberAddress
 * @package system\model
 */
class MemberAddress extends Common {
	protected $table = 'member_address';
	protected $filter = [
		[ 'id', self::EMPTY_FILTER, self::MODEL_BOTH ]
	];
	protected $validate = [
		[ 'id', 'validateId', '地址不属于这个用户', self::NOT_EMPTY_VALIDATE, self::MODEL_UPDATE ],
		[ 'siteid', 'required', '站点编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'uid', 'required', '会员编号不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'username', 'required', '姓名不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'mobile', 'phone', '姓名不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'zipcode', 'zipCode', '邮编格式错误', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'province', 'required', '省份不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'city', 'required', '城市不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'district', 'required', '区/县不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'address', 'required', '详细地址不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
	];

	//验证会员地址
	protected function validateId( $field, $value, $params, $data ) {
		return Db::table( 'member_address' )
		         ->where( 'siteid', SITEID )
		         ->where( 'id', $value )
		         ->where( 'uid', Session::get( 'member.uid' ) )
		         ->first() ? true : false;
	}

	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'uid', 'autoGetUid', 'method', self::NOT_EXIST_AUTO, self::MODEL_BOTH ],
	];

	protected function autoGetUid() {
		return Session::get( 'member.uid' );
	}
}