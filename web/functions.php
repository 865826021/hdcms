<?php
//公共函数库 :)酷酷的

/**
 * 生成后台访问url地址
 *
 * @param $do 执行的动作,一个参数时使用site.php做为控制器,参数为控制器方法, 二个参数时第一个参数为控制器,第二个参数为方法
 * @param $params
 *
 * @return string
 */
function web_url( $do, $params = [ ] ) {
	$url = $url = __ROOT__ . "?s=package/web/entry&i=" . v( 'site.siteid' ) . "&m=" . v( 'current_module' );
	$do  = explode( '.', $do );
	switch ( count( $do ) ) {
		case 1:
			$url .= "&c=site&do={$do[0]}";
			break;
		case 2:
			$url .= "&c={$do[0]}&do={$do[1]}";
	}

	return $params ? $url . '&' . http_build_query( $params ) : $url;
}

/**
 * 生成后台访问url地址
 *
 * @param $do
 * @param $params
 *
 * @return string
 */
function site_url( $do, $params = [ ] ) {
	$info = explode( '/', q( 'get.a' ) );
	switch ( count( explode( '/', $do ) ) ) {
		case 3:
			$url = __ROOT__ . "/index.php?a=" . $do . "&t=site";
			break;
		case 2:
			$url = __ROOT__ . "/index.php?a=" . $info[0] . '/' . $do . "&t=site";
			break;
		case 1:
			$url = __ROOT__ . "/index.php?a=" . $info[0] . '/' . $info[1] . '/' . $do . "&t=site";
			break;
	}

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