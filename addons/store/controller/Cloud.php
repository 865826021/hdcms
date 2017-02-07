<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace addons\store\controller;

/**
 * 云接口
 * Class Cloud
 * @package addons\store\controller
 */
class Cloud {
	/**
	 * 下载最新版HDCMS完整包资料
	 */
	public function downloadFullHdcms() {
		$model = Db::table( 'store_hdcms' )->orderBy( 'id', 'DESC' )->where( 'type', 'full' )->first();
		echo json_encode( $model, JSON_UNESCAPED_UNICODE );
	}
}