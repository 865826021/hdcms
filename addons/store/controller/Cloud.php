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
use addons\store\model\StoreModule;
use addons\store\model\StoreTemplate;
use addons\store\model\StoreUser;
use addons\store\model\StoreZip;
use houdunwang\request\Request;

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
		$res = StoreHdcms::where( 'build', '>', $_POST['build'] )->where( 'type', 'upgrade' )->orderBy( 'id', 'asc' )->first();
		if ( empty( $res ) ) {
			ajax( [ 'message' => '你使用的是最新版HDCMS', 'valid' => 0 ] );
		} else {
			ajax( [ 'message' => '有新版本发布请进行更新', 'hdcms' => $res->toArray(), 'valid' => 1 ] );
		}
	}

	/**
	 * 用户更新CMS系统后修改安装数量
	 */
	public function updateHDownloadNum() {
		//增加下载数量
		Db::table( "store_hdcms" )->where( 'build', $_GET['build'] )->increment( 'update_site_num', 1 );
		ajax( [ 'message' => '更新安装数量成功', 'valid' => 1 ] );
	}

	/**
	 * 根据类型获取模块或模板列表
	 * 用于客户端后台应用商店显示
	 */
	public function apps() {
		switch ( Request::get( 'type' ) ) {
			case 'module':
				$db = StoreModule::paginate( 12 );
				break;
			case 'template':
				$db = StoreTemplate::paginate( 12 );
				break;
		}
		if ( $db ) {
			$data['valid']   = 1;
			$data['message'] = 3434;
			$data['apps']    = $db->toArray();
			$data['page']    = \Page::strList();
		} else {
			$data['valid']   = 0;
			$data['message'] = '获取应用列表失败,请修改再试';
		}
		echo json_encode( $data, true );
		exit;
	}

	/**
	 * 根据编辑获取应用信息
	 * @return string
	 */
	public function getLastAppById() {
		switch ( Request::get( 'type' ) ) {
			case 'module':
				$db = StoreModule::find( Request::get( 'id' ) );
				break;
			case 'template':
				$db = StoreTemplate::find( Request::get( 'id' ) );
				break;
		}
		if ( $db ) {
			$data          = $db->toArray();
			$data['valid'] = 1;
			$data['zip']   = StoreZip::where( 'appid', $db['id'] )->orderBy( 'id', 'DESC' )->first()->toArray();

			return json_encode( $data );
		} else {
			$data['valid']   = 0;
			$data['message'] = '获取应用失败,请稍后再试';
		}
	}
}