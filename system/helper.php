<?php
/**
 * 实例化服务对象
 *
 * @param $name
 *
 * @return mixed
 */
function service( $name ) {
	return Util::instance( $name );
}

/**
 * 生成后台访问url地址
 *
 * @param string $do 执行的动作,一个参数时使用site.php做为控制器,参数为控制器方法, 二个参数时第一个参数为控制器,第二个参数为方法
 * @param $params
 *
 * @return string
 */
function web_url( $do, $params = [ ], $module = NULL ) {
	$do     = str_replace( '.', '/', $do );
	$module = $module ? $module : v( 'module.name' );
	$info   = explode( '/', q( 'get.a' ) );
	switch ( count( explode( '/', $do ) ) ) {
		case 3:
			$url = $do;
			break;
		case 2:
			$url = count( $info ) == 3 ? $info[0] . '/' . $do : $do;
			break;
		case 1:
			$url = count( $info ) == 3 ? $info[0] . '/' . $info[1] . '/' . $do : $info[0] . '/' . $do;
			break;
	}
	$url = __ROOT__ . "/index.php?a=" . $url . "&t=web&siteid=" . SITEID . "&m={$module}";

	$url = $params ? $url . '&' . http_build_query( $params ) : $url;

	return $url . ( IS_MOBILE ? '&mobile=1' : '' );
}

/**
 * 生成后台访问url地址
 *
 * @param string $do
 * @param array $params
 *
 * @return string
 */
function site_url( $do, $params = [ ], $module = NULL ) {
	$do     = str_replace( '.', '/', $do );
	$module = $module ?: v( 'module.name' );
	$info   = explode( '/', q( 'get.a' ) );
	$url    = '';
	switch ( count( explode( '/', $do ) ) ) {
		case 3:
			$url = $do;
			break;
		case 2:
			$url = count( $info ) == 3 ? $info[0] . '/' . $do : $do;
			break;
		case 1:
			$url = count( $info ) == 3 ? $info[0] . '/' . $info[1] . '/' . $do : $info[0] . '/' . $do;
			break;
	}
	$url = __ROOT__ . "/index.php?a=" . $url . "&t=site&siteid=" . SITEID . "&m={$module}";

	return $params ? $url . '&' . http_build_query( $params ) : $url;
}