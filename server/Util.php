<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace server;

//工具类
class Util {
	private $app;

	public function __construct( $app ) {
		$this->app = $app;
	}


//
//
//	/**
//	 * 修改支付状态
//	 *
//	 * @param $tid
//	 */
//	public function paySuccess( $tid ) {
//		Db::table( 'pay' )->where( 'tid', $tid )->update( [ 'status' => 1 ] );
//	}

	/**
	 * 调用服务
	 */
	public function instance( $server ) {
		$server = '\server\\'.ucfirst( $server );

		return new $server;
	}
}