<?php namespace system\middleware;

use houdunwang\request\Request;

/**
 * 应用开始前执行
 * 路由器还没有解析
 * Class AppBegin
 * @package system\middleware
 */
class AppBegin {
	public function run() {
		/**
		 * 设置模块路由规则
		 * 根据站点编号读取该站点规则
		 * 并设置到系统路由队列中
		 */
		if ( isset( $_SERVER['PATH_INFO'] ) ) {
			$pathInfo = trim( $_SERVER['PATH_INFO'], '/' );
			preg_match( '@^([a-z]+)_(\d+)@', $pathInfo, $match );
			if ( count( $match ) == 3 ) {
				//设置站点与模块变量
				Request::set( 'get.siteid', $match[2] );
				Request::set( 'get.m', $match[1] );
			}
		}
		if ( $siteid = Request::get( 'siteid' ) ) {
			$routes = Db::table( 'router' )->where( 'siteid', $siteid )->get();
			foreach ( $routes as $r ) {
				Route::alias( $r['module'] . '_' . $r['router'], $r['url'] );
			}
		}
	}
}