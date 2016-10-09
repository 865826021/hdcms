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
	protected $user;
	//云URL
	protected $url;
	protected $db;

	public function __construct() {
		$this->user = new User();
		service( 'user' )->superUserAuth();
		$user      = Db::table( 'cloud' )->find( 1 );
		$this->url = c( 'api.cloud' ) . "?uid={$user['uid']}&AppSecret={$user['AppSecret']}";
		$this->db  = new \system\model\Cloud();
	}

	/**
	 * 云帐号管理
	 */
	public function account() {
		if ( IS_POST ) {
			$data           = json_decode( $_POST['data'], TRUE );
			$data['weburl'] = __ROOT__;
			$res            = \Cloud::connect( $data );
			if ( $res['valid'] == 1 ) {
				//连接成功
				$data['id']        = 1;
				$data['uid']       = $res['message']['uid'];
				$data['username']  = $res['message']['username'];
				$data['AppSecret'] = $res['message']['AppSecret'];
				$data['webname']   = $res['message']['webname'];
				$data['status']    = 1;
				$this->db->save( $data );
				message( '连接成功', 'refresh', 'success' );
			}
			message( $res['message'], 'back', 'error' );
		}
		if ( ! $field = Db::table( 'cloud' )->find( 1 ) ) {
			$field = [
				'uid'         => 0,
				'username'    => '',
				'webname'     => '',
				'AppID'       => '',
				'AppSecret'   => '',
				'versionCode' => '',
				'releaseCode' => '',
				'createtime'  => 0,
				'status'      => 0
			];
		}

		return view()->with( 'field', $field );
	}

	//检测有没有新版本
	public function checkUpgrade() {
		$hdcms = Db::table('cloud')->find( 1 );
		$d     = \Curl::get( $this->url . "&a=cloud/HdcmsUpgrade&t=web&siteid=1&m=store&releaseCode={$hdcms['releaseCode']}&AppSecret={$hdcms['AppSecret']}" );

		return json_decode( $d, TRUE );
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

				return view( 'downloadLists' )->with( 'data', $data );
				break;
			case 'download':
				$data      = f( '_upgrade_' );
				$fileLists = $data['data']['files'];
				//下载文件,根据文件类型 ADM  AM下载,D删除
				if ( empty( $fileLists ) ) {
					//全部下载完成
					$res = [ 'valid' => 2 ];
				} else {
					//从第一个文件开始下载
					$firstFile = current( $fileLists );
					$action    = strtoupper( $firstFile[0] );
					$file      = trim( substr( $firstFile, 1 ) );
					if ( $action == 'D' ) {
						if ( \Dir::delFile( $file ) ) {
							array_shift( $fileLists );
							$res = [ 'valid' => 1 ];
						} else {
							$res = [ 'valid' => 0 ];
						}
					} else {
						//下载文件
						$postData = [ 'file' => $file, 'releaseCode' => $data['data']['version'][0]['releaseCode'] ];
						$content  = \Curl::post( $this->url . '&a=cloud/download&t=web&siteid=1&m=store', $postData );
						is_dir( dirname( $file ) ) or mkdir( dirname( $file ), 0755, TRUE );
						$res = json_decode( $content, TRUE );

						if ( isset( $res['valid'] ) && $res['valid'] == 0 ) {
							$res = [ 'valid' => 0 ];
						} else {
							if ( file_put_contents( $file, $content ) ) {
								array_shift( $fileLists );
								$res = [ 'valid' => 1 ];
							} else {
								$res = [ 'valid' => 0 ];
							}
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
				$data  = \Curl::get( $this->url . '&a=cloud/HdcmsUpgrade&t=web&siteid=1&m=store&releaseCode=' . $hdcms['releaseCode'] );
				$data  = json_decode( $data, TRUE );
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