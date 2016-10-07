<?php namespace system\service\build;
/**
 * 微站管理服务
 * Class Web
 * @package system\service\build
 */
class Web {
	/**
	 * 获取站点的所有官网数据
	 * @return array
	 */
	public function getSiteWebs() {
		return Db::table( 'web' )->where( 'siteid', SITEID )->orderBy( 'id', 'asc' )->get();
	}

	/**
	 * 检测当前站点中是否存在官网
	 *
	 * @param int $id 站点编号
	 *
	 * @return bool
	 */
	public function has( $id ) {
		return Db::table( 'web' )->where( 'siteid', SITEID )->where( 'id', $id )->first() ? TRUE : FALSE;
	}


	/**
	 * 获取默认站点
	 * @return array
	 */
	public function getDefaultWeb() {
		return Db::table( 'web' )->where( 'siteid', SITEID )->orderBy( 'id', 'asc' )->first();
	}
}