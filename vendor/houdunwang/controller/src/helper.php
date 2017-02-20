<?php
/**
 * 执行控制器方法
 *
 * @param string $action 模块.控制器.方法
 * @param array $args 控制器方法的参数
 *
 * @return bool|mixed
 */
function action( $action, $args = [ ] ) {
	$info  = str_replace( '.', '\\', $action );
	$class = Config::get( 'controller.app' ) . '\\' . $info[0] . '\\controller\\' . ucfirst( $info[1] );
	//控制器不存在执行中间件
	if ( ! class_exists( $class ) ) {
		return false;
	}

	//方法不存在时
	if ( ! method_exists( $class, $info[2] ) ) {
		return false;
	}

	return call_user_func_array( [ new $class, $info[2] ], $args );
}