<?php namespace system\middleware;

use houdunwang\request\Request;

/**
 * 应用开始前执行
 * 路由器还没有解析
 * Class AppBegin
 * @package system\middleware
 */
class AppBegin {
	public function run() {
		//设置路由
		$this->router();
		//加载配置项
		$this->config();
	}

	protected function router() {
		/**
		 * 设置模块路由规则
		 * 根据站点编号读取该站点规则
		 * 并设置到系统路由队列中
		 */
		$url = preg_replace( '@/index.php/@', '', $_SERVER['REQUEST_URI'] );
		$url = trim( $url, '/' );
		if ( preg_match( '@^([a-z]+)(\d+)@', $url, $match ) ) {
			if ( count( $match ) == 3 ) {
				//设置站点与模块变量
				Request::set( 'get.siteid', $match[2] );
				Request::set( 'get.m', $match[1] );
			}
			if ( $siteid = Request::get( 'siteid' ) ) {
				$routes = Db::table( 'router' )->where( 'siteid', $siteid )->get();
				foreach ( $routes as $r ) {
					Route::alias( $r['router'], $r['url'] )->where( json_decode( $r['condition'], true ) );
				}
			}
		}
	}

	/**
	 * 加载系统配置项
	 * 只加载系统配置不加载网站配置
	 * 当install方法执行失败时才会执行config
	 * 即网站安装成功后才有系统配置可加载
	 * 因为那时已经有数据表存在了
	 */
	protected function config() {
		$config             = Db::table( 'config' )->field( 'site,register' )->first();
		$config['site']     = json_decode( $config['site'], true );
		$config['register'] = json_decode( $config['register'], true );
		v( 'config', $config );
		//上传配置
		c( 'upload', array_merge( c( 'upload' ), v( 'config.site.upload' ) ) );
		c( 'app', array_merge( c( 'app' ), v( 'config.site.app' ) ) );
		c( 'http', array_merge( c( 'http' ), v( 'config.site.http' ) ) );
	}
}