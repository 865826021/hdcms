<?php namespace system\service\cloud;

use system\model\Cloud as CloudModel;

/**
 * 远程云请求
 * Class Cloud
 * @package system\service\cloud
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Cloud {
	protected $url = 'http://store.hdcms.com?m=store&siteid=13&action=controller/cloud';
	//云帐号
	protected $accounts;

	public function __construct() {
		$this->accounts = Db::table( 'cloud' )->first();
	}

	/**
	 * 绑定云帐号
	 *
	 * @param $data 帐号、密码、密钥数据
	 *
	 * @return mixed
	 */
	public function connect( $data ) {
		$res   = Curl::post( $this->url . '/connect', $data );
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
		$res = Curl::post( $this->url . '/getUpgradeVersion', $data );
		return json_decode( $res, true );
	}

	/**
	 * 获取远程已经购买的模块列表
	 * @return mixed
	 */
	public function modules() {
		$res = \Curl::post( $this->url . '/', $this->accounts );

		return json_decode( $res, 'true' );
	}
}