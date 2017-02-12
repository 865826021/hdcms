<?php namespace system\model;

/**
 * 路由规则管理
 * Class Router
 * @package system\model
 */
class Router extends Common {
	protected $table = 'router';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'title', 'required', '中文描述不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'router', 'required', '路由规则不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'url', 'required', '匹配地址不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],

	];
	protected $auto = [
		[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'status', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'module', 'getModule', 'method', self::MUST_AUTO, self::MODEL_BOTH ],
	];

	protected function getModule( $val, $data ) {
		return v( 'module.name' );
	}

	/**
	 * 添加路由规则
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function addRouter( $data ) {
		Db::table( 'router' )->where( 'module', v( 'module.name' ) )->delete();
		foreach ( $data as $d ) {
			( new self() )->save( $d );
		}
		return true;
	}

	/**
	 * 获取站点的所有路由规则
	 * @return mixed
	 */
	public function getSiteRouter() {
		return Db::table( 'router' )->where( 'siteid', siteid() )->get();
	}
}