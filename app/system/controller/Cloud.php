<?php namespace app\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
use system\model\User;

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
		ajax(\Cloud::getUpgradeVersion());
	}

	//更新HDCMS
	public function upgrade() {
		switch ( q( 'get.action' ) ) {
			case 'downloadLists':
				$data = f( '_upgrade_' );
				if ( empty( $data['data']['files'] ) ) {
					//文件全部下载完成或本次更新没有修改的文件时,更新数据库
					go( u( 'upgrade', [ 'action' => 'sql' ] ) );
				}
				//下标排序
				sort( $data['data']['files'] );
				f( '_upgrade_', $data );

				return view( 'downloadLists' )->with( 'data', $data );
				break;
			case 'download':
				$data      = f( '_upgrade_' );
				$fileLists = $data['data']['files'];
				if ( empty( $fileLists ) ) {
					//全部下载完成
					$res = [ 'valid' => 2 ];
				} else {
					//文件位置
					$file = current( $fileLists );
					//从第一个文件开始下载
					$action = strtoupper( $file[0] );
					$path   = trim( substr( $file, 1 ) );
					//下载文件,根据文件类型 ADM  AM下载,D删除
					if ( $action == 'D' ) {
						if ( \Dir::delFile( $path ) ) {
							array_shift( $fileLists );
							$res = [ 'valid' => 1 ];
						} else {
							$res = [ 'valid' => 0 ];
						}
					} else {
						//下载文件
						$postData = [ 'file' => $path, 'releaseCode' => $data['data']['version'][0]['releaseCode'] ];
						$content  = \Curl::post( $this->url . '&a=cloud/download&t=web&siteid=1&m=store', $postData );
						Dir::create( dirname( $path ) );
						$res = json_decode( $path, true );
						if ( isset( $res['valid'] ) && $res['valid'] == 0 ) {
							$res = [ 'valid' => 0 ];
						} else {
							file_put_contents( $path, $content );
							array_shift( $fileLists );
							$res = [ 'valid' => 1 ];
						}
					}
				}
				$data['data']['files'] = $fileLists;
				f( '_upgrade_', $data );
				echo json_encode( $res );
				break;
			case 'sql':
				if ( IS_POST ) {
					cli( 'migrate:make' );
					echo json_encode( $res = [ 'valid' => 2 ] );
					exit;
				}

				return view( 'updateSql' )->with( 'data', f( '_upgrade_' ) );
				break;
			case 'finish':
				$data = f( '_upgrade_' );
				//全部更新完成
				$this->db['id']          = 1;
				$this->db['versionCode'] = $data['data']['version'][0]['versionCode'];
				$this->db['releaseCode'] = $data['data']['version'][0]['releaseCode'];
				$this->db['createtime']  = time();
				$this->db->save();
				//删除更新缓存
				f( '_upgrade_', '[del]' );
				message( '恭喜! 系统更新完成', 'upgrade', 'success' );
				break;
			default:
				$hdcms = $this->db->find( 1 );
				$data  = \Curl::get( $this->url . "&a=cloud/HdcmsUpgrade&t=web&siteid=1&m=store&AppSecret={$hdcms['AppSecret']}&releaseCode=" . $hdcms['releaseCode'] );
				$data  = json_decode( $data, true );
				f( '_upgrade_', $data );

				return view()->with( [ 'data' => $data, 'hdcms' => $hdcms ] );
		}
	}

	//清除更新日志
	public function clear() {
		D( '_upgrade_', '[del]' );
		echo '清除更新缓存成功';
	}
}