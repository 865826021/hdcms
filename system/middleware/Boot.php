<?php namespace system\middleware;

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
		$this->install();
	}

	/**
	 * 首次安装时创建数据表与初始数据
	 */
	protected function install() {
		//安装系统时创建表与初始数据
		if ( isset( $_GET['s'] ) && $_GET['s'] == 'install' && is_dir( 'install' ) ) {
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


}