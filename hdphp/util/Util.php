<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace hdphp\util;

//工具类
class Util {
	private $app;

	public function __construct( $app ) {
		$this->app = $app;
	}

	//返回执行时间,单位秒
	public function runtime() {
		return round( microtime( TRUE ) - $_SERVER["REQUEST_TIME_FLOAT"], 2 );
	}

	//获取版本
	public function version() {
		return HDPHP_VERSION;
	}
}