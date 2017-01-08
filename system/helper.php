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