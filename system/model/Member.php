<?php namespace system\model;

use hdphp\model\Model;

//会员管理
class Member extends Model {
	protected $table = 'member';
	protected $auto
	                 = [
			[ 'password', 'setPassword', 'method', 1, 3 ],
			[ 'siteid', 'getSiteid', 'method', 3, 3 ],
			[ 'credit1', 0, 'string', 5, 1 ],
			[ 'credit2', 0, 'string', 5, 1 ],
			[ 'credit3', 0, 'string', 5, 1 ],
			[ 'credit4', 0, 'string', 5, 1 ],
			[ 'credit5', 0, 'string', 5, 1 ],
			[ 'credit6', 0, 'string', 5, 1 ],
			[ 'credit1', 0, 'string', 5, 1 ],
			[ 'createtime', 'time', 'function', 5, 1 ],
			[ 'email', '', 'string', 5, 1 ],
			[ 'mobile', '', 'string', 5, 1 ],
			[ 'qq', '', 'string', 5, 1 ],
			[ 'nickname', '', 'string', 5, 1 ],
			[ 'realname', '', 'string', 5, 1 ],
			[ 'telephone', '', 'string', 5, 1 ],
			[ 'vip', '', 'string', 5, 1 ],
			[ 'address', '', 'string', 5, 1 ],
			[ 'zipcode', '', 'string', 5, 1 ],
			[ 'alipay', '', 'string', 5, 1 ],
			[ 'msn', '', 'string', 5, 1 ],
			[ 'taobao', '', 'string', 5, 1 ],
			[ 'site', '', 'string', 5, 1 ],
			[ 'nationality', '', 'string', 5, 1 ],
			[ 'introduce', '', 'string', 5, 1 ],
			[ 'gender', '', 'string', 5, 1 ],
			[ 'graduateschool', '', 'string', 5, 1 ],
			[ 'height', '', 'string', 5, 1 ],
			[ 'weight', '', 'string', 5, 1 ],
			[ 'bloodtype', '', 'string', 5, 1 ],
			[ 'birthyear', 0, 'string', 4, 1 ],
			[ 'birthmonth', 0, 'string', 4, 1 ],
			[ 'birthday', 0, 'string', 4, 1 ],
			[ 'resideprovince', '', 'string', 4, 1 ],
			[ 'residecity', '', 'string', 4, 1 ],
			[ 'residedist', '', 'string', 4, 1 ],
		];

	//密码字段处理
	public function setPassword( $password, &$data ) {
		$data['security'] = substr( md5( time() ), 0, 10 );

		return md5( $password . $data['security'] );
	}

	//获取站点编号
	public function getSiteid() {
		return Session::get( 'siteid' );
	}

	protected $validate
		= [
			[ 'email', 'unique', '邮箱已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'mobile', 'unique', '手机号已经被使用', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'uid', 'checkUid', '当前用户不属于当前站点', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'password2', 'confirm:password', '两次密码不一致', self::EXISTS_VALIDATE, self::MODEL_BOTH ]
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
	protected function hasUser( $uid ) {
		return Db::table( 'member' )->where( 'siteid', v( 'site.siteid' ) )->where( 'uid', $uid )->first() ? TRUE : FALSE;
	}

}