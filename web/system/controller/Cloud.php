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
		if ( IS_POST ) {
			//下载更新文件,一个一个下载
			$allDown = TRUE;
			foreach ( $_SESSION['_hdcms_upgrade']['message']['upgrade_files'] as $file => $stat ) {
				if ( $stat == FALSE ) {
					$allDown = FALSE;
					$content = Curl::post( 'http://dev.hdcms.com/index.php?a=cloud/download&t=web&siteid=1&m=store', [
						'version' => $_SESSION['_hdcms_upgrade']['message']['version'],
						'file'    => $file
					] );
					if ( ! empty( $content ) ) {
						file_put_contents( 'aaaa.php', $content );
						$_SESSION['_hdcms_upgrade']['message']['upgrade_files'][ $file ] = TRUE;
						echo json_encode( [ 'valid' => 1, 'file' => $file, 'alldown' => 0 ] );
						die;
					}
				}
			}
			if ( $allDown ) {
				echo json_encode( [ 'valid' => 1, 'alldown' => 1 ] );
				die;
			}
		}
		$version                            = Db::table( 'cloud_hdcms' )->where( 'id', 1 )->pluck( 'version' );
		$res                                = \Curl::get( 'http://dev.hdcms.com/index.php?a=cloud/HdcmsUpgrade&t=web&siteid=1&m=store&version=' . $version );
		$_SESSION['_hdcms_upgrade']         = Xml::toSimpleArray( $res );
		$_SESSION['_hdcms_upgrade']['logs'] = nl2br( $_SESSION['_hdcms_upgrade']['logs'] );
		if ( $_SESSION['_hdcms_upgrade']['valid'] == 1 ) {
			//首次下载时将文件列表组合成数组
			if ( ! is_array( $_SESSION['upgrade_files']['upgrade_files'] ) ) {
				//有更新包时,组合更新文件列表
				$files                                                  = preg_split( '/\n|\r/', $_SESSION['_hdcms_upgrade']['message']['upgrade_files'] );
				$_SESSION['_hdcms_upgrade']['message']['upgrade_files'] = [ ];
				foreach ( $files as $v ) {
					$_SESSION['_hdcms_upgrade']['message']['upgrade_files'][ $v ] = FALSE;
				}
			}
		}
		View::with( 'data', $_SESSION['_hdcms_upgrade'] );
		View::make();
	}
}