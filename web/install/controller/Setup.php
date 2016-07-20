<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\install\controller;

/**
 * 安装/卸载程序
 * Class Setup
 * @package web\install\controller
 * @author 向军
 */
class Setup {

	//安装程序
	public function install() {
		//执行SQL安装语句
		$sql = file_get_contents( 'install.sql' );
		Db::sql( $sql );
	}

	//更新程序
	public function upgrade() {
		$sql = file_get_contents( 'upgrade.sql' );
		Db::sql( $sql );
	}
}