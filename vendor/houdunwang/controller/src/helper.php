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
	$info  = explode( '.', $action );
	$class = Config::get( 'controller.app' ) . '\\' . $info[0] . '\\controller\\' . ucfirst( $info[1] );
	$res   = call_user_func_array( [ new $class, $info[2] ], $args );
	echo  $res ;
	die;
}