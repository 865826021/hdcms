<?php namespace system\model;

use hdphp\model\Model;

//会员管理
class Member extends Model {
	protected $table = 'member';
	protected $auto
	                 = [
			[ 'password', 'setPassword', 'method', self::NOT_EMPTY_AUTO, self::MODEL_BOTH ],
			[ 'siteid', 'getSiteid', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'credit1', 0, 'intval', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit2', 0, 'intval', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit3', 0, 'intval', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit4', 0, 'intval', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'credit5', 0, 'intval', self::EXIST_AUTO, self::MODEL_BOTH ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'email', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'mobile', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'qq', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'nickname', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
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
		];

	//密码字段处理
	public function setPassword( $password, &$data ) {
		$data['security']  = substr( md5( time() ), 0, 10 );
		$data['password2'] = md5( $data['password2'] . $data['security'] );

		return md5( $password . $data['security'] );
	}

	//获取站点编号
	public function getSiteid() {
		return Session::get( 'siteid' );
	}

	protected $validate
		= [
			[ 'email', 'unique', '邮箱已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'email', 'email', '邮箱格式错误', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'unique', '手机号已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'phone', '手机号格式错误', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'uid', 'checkUid', '当前用户不属于当前站点', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'password', 'confirm:password2', '两次密码不一致', self::EXIST_VALIDATE, self::MODEL_BOTH ]
		];

	public function checkUid( $field, $value, $params, $data ) {
		return Db::table( $this->table )->where( 'uid', $value )->where( 'siteid', v( 'site.siteid' ) )->first() ? TRUE : FALSE;
	}

	/**
	 * 判断当前uid的用户是否在当前站点中存在
	 *
	 * @param $uid 会员编号
	 *
	 * @return bool
	 */
	public function hasUser( $uid ) {
		return $this->where( 'siteid', v( 'site.siteid' ) )->where( 'uid', $uid )->get() ? TRUE : FALSE;
	}

}