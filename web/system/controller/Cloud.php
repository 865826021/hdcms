<?php namespace web\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
use system\model\Package;
use system\model\User;
use system\model\UserGroup;

/**
 * 云帐号管理
 * Class Cloud
 * @package web\system\controller
 * @author 向军
 */
class Cloud {
	protected $user;

	public function __construct() {
		$this->user = new User();
		if ( ! $this->user->isSuperUser() ) {
			message( '只有系统管理员可以执行操作', 'back', 'error' );
		}
	}

	/**
	 * 云帐号管理
	 */
	public function account() {
		if ( IS_POST ) {
			$res = \Cloud::checkConnect( $_POST['AppID'], $_POST['AppSecret'] );
			if ( $res['valid'] == 1 ) {
				//连接成功
				$data['id']        = 1;
				$data['AppID']     = $_POST['AppID'];
				$data['AppSecret'] = $_POST['AppSecret'];
				Db::table( 'cloud_user' )->replace( $data );
				message( '连接成功', 'refresh', 'success' );
			}
			message( '云服务连接失败', 'back', 'error' );
		}
		$field = Db::table( 'cloud_user' )->find( 1 );
		View::with( 'field', $field );
		View::make();
	}

	/**
	 * 更新HDCMS
	 */
	public function upgrade() {
		$data = D( '_upgrade_' );
		View::with( 'data', $data );
		switch ( q( 'get.action' ) ) {
			case 'downloadLists':
				//显示更新列表,准备执行更新
				$data = D( '_upgrade_' );
				if ( empty( $data['data']['files'] ) ) {
					//文件全部下载完成或本次更新没有修改的文件时,更新数据库
					go( u( 'upgrade', [ 'action' => 'sql' ] ) );
				}
				View::with( 'data', $data );
				View::make( 'downloadLists' );
				break;
			case 'download':
				//下载文件
				if ( empty( $data['data']['files'] ) ) {
					//全部下载完成
					$res = [ 'valid' => 2 ];
				} else {
					//从第一个文件开始下载
					$file = array_shift( $data['data']['files'] );
					D( '_upgrade_', $data );
					$downFile = trim( substr( $file, 1 ) );
					$postData = [ 'file' => $downFile, 'releaseCode' => $data['lastVersion']['releaseCode'] ];
					$content  = \Curl::post( 'http://dev.hdcms.com/index.php?a=cloud/download&t=web&siteid=1&m=store', $postData );
					if ( file_put_contents( $file, $content ) ) {
						$res = [ 'valid' => 1, 'file' => $file ];
					} else {
						$res = [ 'valid' => 0, 'file' => $file ];
					}
				}
				echo json_encode( $res );
				break;
			case 'sql':
				if ( IS_POST ) {
					//执行表操作的SQL
					if ( empty( $data['data']['tables'] ) && empty( $data['data']['fields'] ) ) {
						//全部下载完成
						$res = [ 'valid' => 2 ];
					} else {
						if ( ! empty( $data['data']['tables'] ) ) {
							$t = array_shift( $data['data']['tables'] );
						} else {
							$t = array_shift( $data['data']['tables'] );
						}
						if ( Db::sql( $t['sql'] ) ) {
							D( '_upgrade_', $data );
							$res = [ 'valid' => 1, 'sql' => $t['sql'] ];
						} else {
							$res = [ 'valid' => 0, 'sql' => $t['sql'] ];
						}
					}
					echo json_encode( $res );
					exit;
				}
				if ( empty( $data['data']['tables'] ) && empty( $data['data']['fields'] ) ) {
					//没有更新数据时执行文件更新
					go( 'upgrade', [ 'action' => 'downloadLists' ] );
				}
				View::make( 'updateSql' );
				break;
			case 'finish':
				//全部更新完成
				$data = [
					'versionCode' => $data['lastVersion']['versionCode'],
					'releaseCode' => $data['lastVersion']['releaseCode'],
				];
				Db::table( 'cloud_hdcms' )->where( 'id', 1 )->update( $data );
				//删除更新缓存
				D( '_upgrade_', '[del]' );
				go( 'upgrade' );
				break;
			default:
				if ( empty( $data ) ) {
					$hdcms = Db::table( 'cloud_hdcms' )->find( 1 );
					$data  = \Curl::get( 'http://dev.hdcms.com/index.php?a=cloud/HdcmsUpgrade&t=web&siteid=1&m=store&releaseCode=' . $hdcms['releaseCode'] );
					$tmp   = $data = json_decode( $data, TRUE );
					//本次更新的多个版本中的最新版本
					$data['lastVersion'] = array_pop( $tmp['lists'] );
					D( '_upgrade_', $data );
				}
				View::with( 'data', $data );
				View::with( 'hdcms', $hdcms );
				View::make();
		}

	}
}