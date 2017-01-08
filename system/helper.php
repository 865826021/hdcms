<?php
/*
|--------------------------------------------------------------------------
| HDCMS系统扩展函数库
|--------------------------------------------------------------------------
*/

/**
 * 站点缓存处理
 * 和站点有关的数据都要使用这个函数处理
 * 就是所有与站点相关的缓存数据必须使用这个函数
 *
 * @param string $name 缓存名称
 * @param string $value 缓存数据
 * @param int $expire 过期时间
 *
 * @return mixed
 */
function cache( $name, $value = '[get]', $expire = 0 ) {
	$field = [ 'siteid' => SITEID ];

	return d( $name . ':' . SITEID, $value, $expire, $field );
}

/**
 * 记录日志
 *
 * @param $message
 *
 * @return mixed
 */
function record( $message ) {
	$data['uid']         = v( 'user.info.uid' );
	$data['content']     = $message;
	$data['record_time'] = time();
	$data['url']         = __URL__;
	if ( MODULE == 'system' ) {
		//系统应用管理
		$data['system_module'] = 1;
		$data['siteid']        = Request::get( 'siteid' ) ?: 0;
	} else {
		//站点管理时记录站点编号
		$data['system_module'] = 0;
		$data['siteid']        = SITEID;
	}

	return Db::table( 'log' )->insert( $data );
}