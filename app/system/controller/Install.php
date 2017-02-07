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
	public function table() {
		cli( 'hd migrate:make' );
		message( '数据表创建成功', '', 'success' );
	}

	/**
	 * 添加初始数据成功
	 */
	public function data() {
		cli( 'hd seed:make' );
		message( '初始数据插入成功', '', 'success' );
	}
}