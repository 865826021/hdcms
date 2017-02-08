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

use addons\store\model\StoreHdcms;
use addons\store\model\StoreUser;

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

	/**
	 * 绑定云帐号
	 */
	public function connect() {
		$res = \Member::login( $_POST, true );
		if ( $res === true ) {
			//登录成功
			$model           = StoreUser::where( 'uid', Session::get( 'member_uid' ) )->first();
			$model['secret'] = $_POST['secret'];
			$model->save();
			ajax( [ 'message' => '连接成功', 'uid' => $model['uid'], 'valid' => 1 ] );
		} else {
			ajax( [ 'message' => $res, 'valid' => 0 ] );
		}
	}

	/**
	 * 检测用户提供的版本有无可用更新
	 */
	public function getUpgradeVersion() {
		$res = StoreHdcms::where( 'createtime', '>', $_POST['createtime'] )->where( 'type', 'upgrade' )->orderBy( 'id', 'asc' )->first();
		if ( empty( $res ) ) {
			ajax( [ 'message' => '你使用的是最新版HDCMS', 'valid' => 0 ] );
		} else {
			ajax( [ 'message' => '有新版本发布请进行更新', 'hdcms' => $res->toArray(), 'valid' => 1 ] );
		}
	}
}









