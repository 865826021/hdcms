<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace server;

/**
 * 服务工具类
 * Class Util
 * @package server
 */
class Util {
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
	 * 根据标识验证访问权限
	 *
	 * @param $identify
	 * @param $type
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

	/**
	 * 超级管理员检测
	 *
	 * @param string $action jump 跳转 return 返回
	 *
	 * @return bool
	 */
	function superUser( $action = 'jump' ) {
		if ( v( 'user.system.super_user' ) ) {
			return TRUE;
		}
		switch ( $action ) {
			case 'jump':
				message( '你不是系统管理员,无法执行该功能', 'back', 'error' );
				break;
			case 'return':
				return FALSE;
		}
	}

	/**
	 * 实例子服务器
	 *
	 * @param string $name 服务名
	 *
	 * @return mixed
	 */
	public function instance( $name ) {
		static $instances = [ ];
		if ( isset( $instances[ $name ] ) ) {
			return $instances[ $name ];
		}
		$class = '\server\build\\' . $name;

		return $instances[ $name ] = new $class;
	}
}