<?php namespace system\service\cloud;

/**
 * 远程云请求
 * Class Cloud
 * @package system\service\cloud
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Cloud {
	protected $url = 'http://open.hdcms.com';
	//云帐号
	protected $accounts;

	public function __construct() {
		$this->accounts = Db::table( 'cloud' )->first();
	}

	/**
	 * 获取远程已经购买的模块列表
	 * @return mixed
	 */
	public function modules() {
		$res = \Curl::post( $this->url . '/', $this->accounts );

		return json_decode( $res, 'true' );
	}

	/**
	 * 远程POST请求
	 *
	 * @param string $url 地址
	 * @param array $post POST数据
	 *
	 * @return mixed
	 */
	public function post( $url, $post = [ ] ) {
		$res = \Curl::post( $this->url . '/' . $url, $post );

		return json_decode( $res, 'true' );
	}
}