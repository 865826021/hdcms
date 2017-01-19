<?php namespace system\service\navigate;

use system\service\Common;

/**
 * 站点导航菜单管理
 * Class Navigation
 * @package system\service\member
 */
class Navigate extends Common {

	/**
	 * 获取菜单类型的中文标题
	 *
	 * @param string $entry 类型标识
	 *
	 * @return mixed
	 */
	public function title( $entry = '' ) {
		$entry = $entry ?: Request::get( 'entry' );
		$menu  = [ 'home' => '微站首页导航', 'profile' => '手机会员中心导航', 'member' => '桌面会员中心导航' ];

		return $menu[ $entry ];
	}
}