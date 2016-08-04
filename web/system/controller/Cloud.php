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
		switch ( q( 'get.action' ) ) {
			case 'downloadLists':
				//显示更新列表,准备执行更新
				$data = F( '_upgrade_' );
				if ( empty( $data['files'] ) ) {
					//文件全部下载完成或本次更新没有修改的文件时,更新数据库
					go( u( 'upgrade', [ 'action' => 'sql' ] ) );
				}
				View::with( 'data', $data );
				View::make( 'downloadLists' );
				break;
			case 'download':
				//下载文件
				$data     = F( '_upgrade_' );
				$file     = trim( substr( current( $data['data']['files'] ), 1 ) );
				$postData = [ 'file' => $file, 'releaseCode' => $data['lastVersion']['releaseCode'] ];
				$content  = \Curl::post( 'http://dev.hdcms.com/index.php?a=cloud/download&t=web&siteid=1&m=store', $postData );
				if ( file_put_contents( $file, $content ) ) {
					array_shift( $data['data']['files'] );
					F( '_upgrade_', $data );
					echo 1;
				}
				break;
			case 'sql':
				$data = F( '_upgrade_' );
				if ( IS_POST ) {
					//执行表操作的SQL
					foreach ( $data['data']['tables'] as $d ) {
						if ( ! Db::sql( $d['sql'] ) ) {
							message( '执行失败: ' . $d['sql'], 'back', 'error' );
						}
					}
					//执行字段操作的SQL
					foreach ( $data['data']['fields'] as $d ) {
						if ( ! Db::sql( $d['sql'] ) ) {
							message( '执行失败: ' . $d['sql'], 'back', 'error' );
						}
					}
					message( '操作成功', 'back', 'success' );
				}
				View::make( 'updateSql' );
				//执行SQL
				break;
			default:
				$hdcms = Db::table( 'cloud_hdcms' )->find( 1 );
				$data  = \Curl::get( 'http://dev.hdcms.com/index.php?a=cloud/HdcmsUpgrade&t=web&siteid=1&m=store&releaseCode=' . $hdcms['releaseCode'] );
				$tmp   = $data = json_decode( $data, TRUE );
				//本次更新的多个版本中的最新版本
				$data['lastVersion'] = array_pop( $tmp['lists'] );
				F( '_upgrade_', $data );
				View::with( 'data', $data );
				View::with( 'hdcms', $hdcms );
				View::make();
		}

	}
}