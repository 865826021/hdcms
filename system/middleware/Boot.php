<?php namespace system\middleware;

/**
 * 应用启动时执行的中间件
 * 不能进行SESSION操作
 * Class Boot
 * @package system\middleware
 */
class Boot {
	public function run() {
		//安装系统时创建表与初始数据
		if ( isset( $_GET['s'] ) && $_GET['s'] == 'install' ) {
			cli( 'hd migrate:make' );
			cli( 'hd seed:make' );
			message( '数据表创建成功', '', 'success' );
		}
	}
}