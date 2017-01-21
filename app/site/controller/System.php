<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace app\site\controller;


class System {
	public function __construct() {
		\User::isOperate();
	}

	/**
	 * 更新站点缓存
	 */
	public function updateCache() {
		if ( IS_POST ) {
			\Site::updateCache();
			message( '更新站点缓存成功', 'back', 'success' );
		}

		return view();
	}
}