<?php
//公共函数库 :)酷酷的

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

/**
 * 检测产点是否临时关闭(日常维护)
 * @return bool
 */
function checkSiteClose() {
	$site = m( 'Config' )->getByName( 'site' );
	if ( ! $site['is_open'] ) {
		session_unset();
		View::with( 'close_message', $site['close_message'] );
		View::make( 'resource/view/site_close.html' );
	}
}

/**
 * 根据标识验证访问权限
 */
function auth( $identify, $type ) {
	if ( ( new \system\model\User() )->auth( $identify, $type ) === FALSE ) {
		message( '你没有操作权限', u( 'site/entry/refer', [ 'siteid' => SITEID ] ), 'warning' );
	}
}

/**
 * 调用模块方法
 *
 * @param string $module 模块.方法
 * @param array $params 方法参数
 *
 * @return mixed
 */
function api( $module, $params ) {
	static $instance = [ ];
	$info = explode( '.', $module );
	if ( ! isset( $instance[ $module ] ) ) {
		$data                = Db::table( 'modules' )->where( 'name', $info[0] )->first();
		$class               = 'addons\\' . $data['name'] . '\api';
		$instance[ $module ] = new $class;
	}

	return call_user_func_array( [ $instance[ $module ], $info[1] ], $params );
}