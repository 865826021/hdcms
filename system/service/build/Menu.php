<?php namespace system\service\build;
/**
 * 站点后台菜单管理服务
 * Class Menu
 * @package system\service\build
 */
class Menu extends \system\model\Menu {
	/**
	 * 获取菜单组合的父子级多维数组
	 * @return mixed
	 */
	public function getLevelMenuLists() {
		return Data::channelLevel( $this->get(), 0, '', 'id', 'pid' );
	}

	/**
	 * 获取当前帐号后台访问菜单
	 * @return mixed
	 */
	public function getMenus( $show = TRUE ) {
		//移除用户没有使用权限的菜单
		$permission = Db::table( 'user_permission' )
		                ->where( 'siteid', SITEID )
		                ->where( 'uid', Session::get( 'user.uid' ) )
		                ->where( 'type', 'system' )
		                ->pluck( 'permission' );
		$menus      = $this->get();
		if ( $permission ) {
			$permission = explode( '|', $permission );
			$tmp        = $menus;
			foreach ( $tmp as $k => $m ) {
				if ( $m['permission'] != '' && ! in_array( $m['permission'], $permission ) ) {
					unset( $menus[ $k ] );
				}
			}
		}
		$menus = Data::channelLevel( $menus, 0, '', 'id', 'pid' );
		//移除没有三级菜单的一级与二级菜单
		$tmp = $menus;
		foreach ( $tmp as $k => $t ) {
			foreach ( $t['_data'] as $n => $d ) {
				if ( empty( $d['_data'] ) ) {
					unset( $menus[ $k ]['_data'][ $n ] );
				}
			}
			if ( empty( $menus[ $k ]['_data'] ) ) {
				unset( $menus[ $k ] );
			}
		}
		//插件模块列表
		$allowModules = Db::table( 'user_permission' )->where( 'siteid', SITEID )->where( 'uid', Session::get( 'user.uid' ) )->lists( 'type' );
		//获取模块按行业类型
		$modules = ( new Modules() )->getModulesByIndustry( $allowModules );
		//模块菜单
		foreach ( v( 'modules' ) as $v ) {
			if ( $v['name'] == v( 'module.name' ) ) {
				$moduleLinks = $v;
				break;
			}
		}

		if ( $show ) {
			View::with( '_site_menu_', $menus );
			View::with( '_site_menu_modules_', $modules );
			View::with( '_site_modules_menu_', $moduleLinks );
		}

		return $menus;
	}
}