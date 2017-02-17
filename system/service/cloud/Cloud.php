<?php namespace system\service\cloud;

use system\model\Cloud as CloudModel;
use system\model\Modules;

/**
 * 远程云请求
 * Class Cloud
 * @package system\service\cloud
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Cloud {
	//云主机
	protected $host = 'http://store.hdcms.com';
	protected $url = 'http://store.hdcms.com?m=store&siteid=13&action=controller';
	//云帐号
	protected $accounts;

	public function __construct() {
		$this->accounts = Db::table( 'cloud' )->first();
	}

	//验证云帐号状态
	public function checkAccount() {
		if ( $this->accounts['status'] == 0 ) {
			message( '请先绑定云帐号后执行操作', u( 'cloud.account' ), 'warning' );
		}
	}

	/**
	 * 绑定云帐号
	 *
	 * @param $data 帐号、密码、密钥数据
	 *
	 * @return mixed
	 */
	public function connect( $data ) {
		$res   = Curl::post( $this->url . '/cloud/connect', $data );
		$res   = json_decode( $res, true );
		$model = CloudModel::find( 1 );
		if ( $res['valid'] == 1 ) {
			//连接成功
			$model['uid']      = $res['uid'];
			$model['username'] = $data['username'];
			$model['secret']   = $data['secret'];
			$model['webname']  = $data['webname'];
			$model['status']   = 1;
			$model->save();
		} else {
			$model['status'] = 0;
			$model->save();
		}

		return $res;
	}

	/**
	 * 检测HDCMS有没有新版本可用来更新
	 * @return array
	 */
	public function getUpgradeVersion() {
		$data = CloudModel::find( 1 )->toArray();
		$res  = Curl::post( $this->url . '/cloud/getUpgradeVersion', $data );

		return json_decode( $res, true );
	}

	/**
	 * 更新HDCMS安装数量
	 * @return mixed
	 */
	public function updateHDownloadNum() {
		$res = Curl::post( $this->url . '/cloud/updateHDownloadNum&build='
		                   . Db::table( 'cloud' )->where( 'id', 1 )->pluck( 'build' ) );

		return json_decode( $res, true );
	}

	/**
	 * 下载HDCMS更新包
	 * @return array
	 */
	public function downloadUpgradeVersion() {
		$soft    = $this->getUpgradeVersion();
		$content = \Curl::get( $this->host . '/' . $soft['hdcms']['file'] );
		\Dir::create( 'upgrade/hdcms' );
		file_put_contents( 'upgrade/hdcms.zip', $content );
		chdir( 'upgrade' );
		Zip::PclZip( 'hdcms.zip' );//设置压缩文件名
		Zip::extract();//解压缩到当前目录
		chdir( '..' );

		//将旧版本文件进行备份
		$files   = file_get_contents( 'upgrade/hdcms/upgrade_files.php' );
		$files   = preg_split( '/\n/', $files );
		$current = Db::table( 'cloud' )->find( 1 );
		foreach ( $files as $f ) {
			$info = preg_split( '/\s+/', $f );
			\Dir::copyFile( $info[1], "upgrade/{$current['version']}/{$info[1]}" );
		}
		//直接文件替换操作
		$formats = [ ];
		foreach ( $files as $f ) {
			$info      = preg_split( '/\s+/', $f );
			$formats[] = [ 'type' => $info[0], 'file' => $info[1] ];
		}
		//验证结果
		$valid = 1;
		foreach ( $formats as $k => $info ) {
			switch ( $info['type'] ) {
				case 'D':
					//删除文件
					if ( \Dir::delFile( $info[1] ) === false ) {
						$valid                   = 0;
						$formats[ $k ]['status'] = false;
					} else {
						$formats[ $k ]['status'] = true;
					}
					break;
				default:
					if ( \Dir::copyFile( "upgrade/hdcms/" . $info['file'], $info['file'] ) === false ) {
						$formats[ $k ]['status'] = false;
					} else {
						$formats[ $k ]['status'] = true;
					}
			}
		}

		return [ 'files' => $formats, 'valid' => $valid ];
	}

	/**
	 * 获取模块或模板列表
	 *
	 * @param $type 类型 module/template
	 * @param $page 页数
	 *
	 * @return mixed
	 */
	public function apps( $type, $page ) {
		$content = \Curl::get( $this->url . "/cloud/apps&type={$type}&page={$page}" );
		$apps    = json_decode( $content, true );
		//已经安装的所模块
		$modules = Modules::lists( 'name' );
		foreach ( $apps['apps'] as $k => $v ) {
			//是否安装
			$apps['apps'][ $k ]['is_install'] = false;
			if ( in_array( $v['name'], $modules ) ) {
				$apps['apps'][ $k ]['is_install'] = true;
			}
		}

		return $apps;
	}

	/**
	 * 下载应用
	 *
	 * @param $type
	 * @param $id
	 */
	public function downloadApp( $type, $id ) {
		//获取模块信息
		$app = \Curl::get( $this->url . "/cloud/getLastAppById&type={$type}&id={$id}" );
		$app = json_decode( $app, true );
		if ( Modules::where( 'name', $app['name'] )->get() ) {
			message( '应用已经安装,不允许重复安装!', '', 'error' );
		}
		if ( ! is_file( "addons/{$app['name']}/cloud.app" ) ) {
			message( '已经存在本地开发的同名模块', u( 'shop.lists' ), 'error' );
		}
		$content = \Curl::get( $this->host . "/{$app['zip']['file']}" );
		$file    = "addons/{$app['name']}.zip";
		file_put_contents( $file, $content );
		Zip::PclZip( $file );//设置压缩文件名
		Zip::extract( 'addons' );
		@unlink( $file );

		file_put_contents( "addons/{$app['name']}/cloud.app", '来自云应用' );
		ajax( [
			'message' => '数据下载完成,准备开始安装',
			'config'  => $app,
			'valid'   => 1
		] );
	}
}







