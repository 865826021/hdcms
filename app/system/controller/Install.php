<?php namespace app\system\controller;
/**
 * 安装初始数据
 * Class Install
 * @package app\system\controller
 */
class Install {
	/**
	 * 创建数据表
	 */
	public function make() {
		cli( 'hd migrate:make' );
		cli( 'hd seed:make' );
		message( '数据表创建成功', '', 'success' );
	}
}