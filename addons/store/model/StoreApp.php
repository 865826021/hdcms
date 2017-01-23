<?php namespace addons\store\model;

use houdunwang\model\Model;

/**
 * 模块/模板应用
 * Class StoreApp
 * @package addons\store\model
 */
class StoreApp extends Model {
	protected $table = 'store_app';
	protected $validate = [
		[ 'title', 'required', '标题不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'name', 'required', '标识不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'type', 'required', '应用类型不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
		[ 'thumb', 'required', '缩略图不能为空', self::EMPTY_VALIDATE, self::MODEL_INSERT ],
	];
	protected $auto = [
		[ 'uid', 'getUid', 'method', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'price', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'racking', 1, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];

	protected function getUid() {
		return Session::get( "user.uid" );
	}

	/**
	 * 获取模块的最新版数据
	 *
	 * @param $module
	 *
	 * @return mixed
	 */
	public function getLastModule( $module ) {
		return $this->join( 'store_app_package', 'store_apps_package.appid', '=', 'store_app.id' )
		            ->where( 'store_app.name', $module )
		            ->where( 'racking', 1 )
		            ->orderBy( 'store_app_package.id', 'DESC' )
		            ->field( 'store_app_package.releaseCode,store_app_package.package' )
		            ->first();
	}
}