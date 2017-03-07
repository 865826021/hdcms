<?php namespace app\system\controller;

	/** .-------------------------------------------------------------------
	 * |  Software: [HDCMS framework]
	 * |      Site: www.hdcms.com
	 * |-------------------------------------------------------------------
	 * |    Author: 向军 <2300071698@qq.com>
	 * |    WeChat: aihoudun
	 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
	 * '-------------------------------------------------------------------*/
/**
 * 云帐号管理
 * Class Cloud
 * @package web\system\controller
 * @author 向军
 */
class Cloud {
	public function __construct() {
		\User::superUserAuth();
	}

	/**
	 * 绑定云帐号
	 * 这是进行系统更新的前提
	 */
	public function account() {
		if ( IS_POST ) {
			$data           = json_decode( $_POST['data'], true );
			$data['weburl'] = __ROOT__;
			$res            = \Cloud::connect( $data );
			if ( $res['valid'] == 1 ) {
				message( '连接成功', 'refresh', 'success' );
			} else {
				message( $res['message'], 'back', 'error' );
			}
		}
		$field = \system\model\Cloud::find( 1 )->toArray();
		if ( empty( $field['secret'] ) ) {
			$field['secret'] = md5( time() . mt_rand( 1, 9999 ) );
		}

		return view()->with( 'field', $field );
	}

	//检测有没有新版本
	public function getUpgradeVersion() {
		ajax( \Cloud::getUpgradeVersion() );
	}

	//更新HDCMS
	public function upgrade() {
		switch ( q( 'get.action' ) ) {
			case 'download':
				return view( 'download' );
				break;
			case 'downloadFile':
				//下载更新包
				$files = \Cloud::downloadUpgradeVersion();
				ajax( $files );
				break;
			case 'sql':
				//更新SQL
				if ( IS_POST ) {
					cli( 'hd migrate:make' );
					cli( 'hd seed:make' );
					ajax( [ 'valid' => 1, 'message' => '数据表更新成功' ] );
				}

				return view( 'updateSql' );
				break;
			case 'finish':
				$hdcms = \Cloud::getUpgradeVersion();
				Db::table( 'cloud' )->where( 'id', 1 )->update( [ 'build'   => $hdcms['hdcms']['build'],
				                                                  'version' => $hdcms['hdcms']['version']
				] );
				\Cloud::updateHDownloadNum();
				message( '恭喜! 系统更新完成', 'upgrade', 'success' );
				break;
			default:
				//获取更新版本
				$upgrade = \Cloud::getUpgradeVersion();
				return view()->with( [ 'upgrade' => $upgrade, 'current' => \system\model\Cloud::find( 1 ) ] );
		}
	}
}