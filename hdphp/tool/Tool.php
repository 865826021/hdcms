<?php namespace hdphp\tool;
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

class Tool {
	private $app;

	public function __construct( $app ) {
		$this->app = $app;
	}

	/**
	 * 生成随机数字
	 *
	 * @param int $len 数量
	 *
	 * @return string
	 */
	public function randNum( $len = 4 ) {
		$str = '0123456789';
		$s   = '';
		for ( $i = 0;$i < $len;$i ++ ) {
			$pos = mt_rand( 0, strlen( $str ) -1);
			$s .= $str[ $pos ];
		}

		return $s;
	}
}