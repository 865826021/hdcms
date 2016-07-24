<?php namespace web\system\controller;
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

class Hdcms {
	protected $dirs
		= [
			'data',
			'hdphp',
			'install',
			'module',
			'resource',
			'server',
			'system',
			'theme/default',
			'ucenter',
			'web'
		];

	/**
	 * 创建HDCMS升级包
	 */
	public function upgrade() {
		$res = \Cloud::getHdcmsLatestVersion();
		foreach ( $this->dirs as $d ) {
			\Dir::copy( $d, 'tmp/' . basename( $d ) );
		}
		$this->filterFiles( $res['message']['createtime'] );
	}

	protected function filterFiles( $createtime, $dir = 'tmp' ) {
		foreach ( glob( $dir . "/*" ) as $v ) {
			if ( is_dir( $v ) ) {
				$this->filterFiles( $createtime, $v );
			} else if ( filemtime( $v ) <= $createtime ) {
				unlink( $v );
			}
		}
	}
}