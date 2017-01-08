<?php namespace app\system\controller;

/**
 * 模块菜单和菜单组欢迎页
 * Class Manage
 * @package app\system\controller
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Manage {
	/**
	 * 系统设置图标列表菜单
	 * @return mixed
	 */
	public function menu() {
		\User::loginAuth();

		return view()->with( [ 'isSuperUser' => \User::isSuperUser() ] );
	}

	/**
	 * 更新缓存
	 * @return mixed
	 */
	public function updateCache() {
		\User::isSuperUser();
		if ( IS_POST ) {
			//更新数据缓存
			if ( isset( $_POST['data'] ) ) {
				Dir::del( ROOT_PATH . '/storage/cache' );
			}
			//更新模板缓存
			if ( isset( $_POST['tpl'] ) ) {
				Dir::del( ROOT_PATH . '/storage/view' );
			}
			//微信缓存
			if ( isset( $_POST['weixin'] ) ) {
				Dir::del( ROOT_PATH . '/storage/weixin' );
			}
			//更新所有站点缓存
			\Site::updateAllCache();
			message( '缓存更新成功', 'menu' );
		}

		return view();
	}
}