<?php namespace system\middleware;

use houdunwang\db\Db;
use houdunwang\route\Route;

/**
 * 应用启动时执行的中间件
 * 不能进行SESSION操作
 * 不能执行数据库操作
 * Class Boot
 * @package system\middleware
 */
class Boot {
	//自动执行的方法
	public function run() {
		//数据库连接配置
		Config::set( 'database', array_merge( c( 'database' ), include 'data/database.php' ) );
		//安装检测
		$this->install();
		//加载配置项
		$this->config();
		//分析模块域名
		$this->parseDomain();
		//设置路由
		$this->router();
	}

	/**
	 * 首次安装时创建数据表与初始数据
	 * 如果存在install.php文件时
	 * 系统未进行安装执行安装脚本
	 */
	protected function install() {
		//安装系统时创建表与初始数据
		if ( isset( $_GET['a'] ) && $_GET['a'] == 'initialization' ) {
			cli( 'hd migrate:make' );
			cli( 'hd seed:make' );
			message( '数据表创建成功', '', 'success' );
		}

		/**
		 * 安装检测
		 * 安装结束后会删除install.php文件
		 */
		if ( ! is_file( 'data/lock.php' ) && is_file( 'install.php' ) ) {
			go( __ROOT__ . '/install.php' );
		}
	}

	/**
	 * 加载系统配置项
	 * 只加载系统配置不加载站点配置
	 * 即网站安装成功后才有系统配置可加载
	 * 因为那时已经有数据表存在了
	 */
	protected function config() {
		$config             = Db::table( 'config' )->field( 'site,register' )->first();
		$config['site']     = json_decode( $config['site'], true );
		$config['register'] = json_decode( $config['register'], true );
		v( 'config', $config );
		//上传配置
		if ( $upload = v( 'config.site.upload' ) ) {
			c( 'upload', array_merge( c( 'upload' ), $upload ) );
			c( 'upload.size', c( 'upload.size' ) * 1024 );
		}
		if ( $app = v( 'config.site.app' ) ) {
			c( 'app', array_merge( c( 'app' ), $app ) );
		}
		if ( $http = v( 'config.site.http' ) ) {
			c( 'http', array_merge( c( 'http' ), $http ) );
		}
		if ( $oss = v( 'config.site.oss' ) ) {
			c( 'oss', array_merge( c( 'oss' ), $oss ) );
		}
	}

	/**
	 * 设置模块路由规则
	 * 根据站点编号读取该站点规则
	 * 并设置到系统路由队列中
	 */
	protected function router() {
		$url = preg_replace( '@/index.php/@', '', $_SERVER['REQUEST_URI'] );
		$url = trim( $url, '/' );
		if ( preg_match( '@^([a-z]+)(\d+)@', $url, $match ) ) {
			if ( count( $match ) == 3 ) {
				//设置站点与模块变量
				Request::set( 'get.siteid', $match[2] );
				Request::set( 'get.m', $match[1] );
			}
			if ( $siteid = Request::get( 'siteid' ) ) {
				$routes = Db::table( 'router' )->where( 'siteid', $siteid )->where( 'status', 1 )->get();
				foreach ( $routes as $r ) {
					Route::alias( $r['router'], $r['url'] )->where( json_decode( $r['condition'], true ) );
				}
			}
		}
	}

	/**
	 * 地址中不存在动作标识
	 * s m action 时检测域名是否已经绑定到模块
	 * 如果存在绑定的模块时设置当请求的的模块
	 */
	protected function parseDomain() {
		$domain       = trim( $_SERVER['HTTP_HOST'] . dirname( $_SERVER['SCRIPT_NAME'] ), '/\\' );
		$moduleDomain = Db::table( 'module_domain' )->where( 'domain', $domain )->first();
		if ( $moduleDomain ) {
			if ( ! Request::get( 'siteid' ) ) {
				Request::set( 'get.siteid', $moduleDomain['siteid'] );
			}
			if ( ! Request::get( 'm' ) && ! Request::get( 's' ) && ! Request::get( 'action' ) ) {
				Request::set( 'get.m', $moduleDomain['module'] );
			}
		}
	}
}