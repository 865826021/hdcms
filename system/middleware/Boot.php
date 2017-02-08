<?php namespace system\middleware;

/**
 * 应用启动时执行的中间件
 * 不能进行SESSION操作
 * Class Boot
 * @package system\middleware
 */
class Boot {
	//自动执行的方法
	public function run() {
		$this->install();
		$this->config();
	}

	/**
	 * 首次安装时创建数据表与初始数据
	 */
	protected function install() {
		//安装系统时创建表与初始数据
		if ( isset( $_GET['s'] ) && $_GET['s'] == 'install' ) {
			cli( 'hd migrate:make' );
			cli( 'hd seed:make' );
			message( '数据表创建成功', '', 'success' );
		}
		/**
		 * 安装检测
		 * 安装结束后会删除install.php文件
		 */
		if ( is_file( 'install.php' ) ) {
			go( __ROOT__ . '/install.php' );
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