<?php
/*
|--------------------------------------------------------------------------
| HDCMS系统扩展函数库
|--------------------------------------------------------------------------
*/
function siteid() {
	return SITEID;
}

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

/**
 * 验证后台管理员帐号在当前站点的权限
 * 如果当前有模块动作时同时会验证帐号访问该模块的权限
 */
function auth() {
	\User::auth();
}

/**
 * 根据标识验证模块的访问权限
 * 系统模块时使用 system标识验证,因为所有系统模块权限是统一管理的
 * 插件模块是独立设置的,所以针对插件使用插件名标识进行验证
 *
 * @param $tag 权限标识
 */
function authIdentity( $tag ) {
	\User::authIdentity( $tag );
}


/**
 * 生成模块控制器链接地址
 *
 * @param string $action 动作标识
 * @param array $args GET参数
 *
 * @return string
 */
function url( $action, $args = [ ] ) {
	$info = preg_split( '#\.|/#', $action );

	return __ROOT__ . "/?m=" . v( 'module.name' ) . "&action=" .
	       implode( '/', $info ) . ( $args ? '&' . http_build_query( $args ) : '' );
}