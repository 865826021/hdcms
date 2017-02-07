<?php namespace system\middleware;

/**
 * 应用启动时执行的中间件
 * 不能进行SESSION操作
 * Class Boot
 * @package system\middleware
 */
class Boot {
	public function run() {
		if ( Request::get( 's' ) == 'install' ) {
			cli( 'hd migrate:make' );
			cli( 'hd seed:make' );
			message( '数据表创建成功', '', 'success' );
		}
	}
}