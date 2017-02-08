<?php
/**
 * 执行控制器方法
 *
 * @param $url 控制器方法标识
 * 如:system.user.add 可以使用\分隔
 */
function controller( $url ) {
	$url = str_replace( '.', '\\', $url );
	\houdunwang\request\Request::set( 'get.'.Config::get( 'http.url_var' ), $url );
	\houdunwang\controller\Controller::run();
}