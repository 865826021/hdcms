<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\db;
use houdunwang\framework\build\Provider;

/**
 * Class DbProvider
 * @package hdphp\db
 */
class DbProvider extends Provider {
	//延迟加载
	public $defer = true;

	public function boot() {
		//加载.env配置
		if ( is_file( '.env' ) ) {
			$config = [ ];
			foreach ( file( '.env' ) as $file ) {
				$data = explode( '=', $file );
				if ( count( $data ) == 2 ) {
					$config[ trim( $data[0] ) ] = trim( $data[1] );
				}
			}
			Config::set( 'database.host', $config['DB_HOST'] );
			Config::set( 'database.user', $config['DB_USER'] );
			Config::set( 'database.password', $config['DB_PASSWORD'] );
			Config::set( 'database.database', $config['DB_DATABASE'] );
		}
		//如果主机是LOCALHOST改为IP地址
		$host = preg_match( '@localhost@i', c( 'database.host' ) ) ? '127.0.0.1' : c( 'database.host' );
		c( 'database.host', $host );
		//将公共数据库配置合并到 write 与 read 中
		$config = Config::getExtName( 'database', [ 'write', 'read' ] );
		if ( empty( $config['write'] ) ) {
			$config['write'][] = Config::getExtName( 'database', [
				'write',
				'read'
			] );
		}
		if ( empty( $config['read'] ) ) {
			$config['read'][] = Config::getExtName( 'database', [
				'write',
				'read'
			] );
		}
		//重设配置
		Config::set( 'database', $config );
		//缓存表字段
		Config::set( 'database.cache_field', ! Config::get( 'app.debug' ) );
	}

	public function register() {
		$this->app->bind( 'Db', function () {
			return new Db();
		} );
	}
}