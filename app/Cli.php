<?php namespace app;

use houdunwang\cli\build\Base;
use houdunwang\dir\Dir;

/**
 * 命令处理
 * Class Cms
 * @package app
 */
class Cli extends Base {
	//编译工作目录
	protected $path = '/Users/xj/Desktop/hdcms';
	//软件目录
	protected $hdcmsDir;
	//完整版过滤的目录
	protected $filterFullDirectory = [ 'addons', 'data', 'install', 'storage', 'attachment' ];

	public function __construct() {
		parent::__construct();
		$this->hdcmsDir = realpath( '.' );
	}

	//不生成到更新压缩包的文件
	protected $filterUpgradeFiles = [
		'data/database.php'
	];

	/**
	 * 同时生成完整与更新压缩包
	 */
	public function make() {
		$this->install();
		$this->full();
		$this->upgrade();
	}

	//生成版本编号
	public function version() {
		exec( "git log -1", $logs );
		p($logs);exit;
		$logs = preg_split( '@\s+@', $logs[0] );
		p( $logs );
		chdir( dirname( __DIR__ ) );
		exec( "git tag -l", $tags );
		$data['version'] = array_pop( $tags );
		$data['build']   = time();
		file_put_contents( 'version.php', "<?php return " . var_export( $data, true ) . ';' );
	}

	//生成安装脚本
	public function install() {
		\Curl::get( 'http://localhost/hdcms/install/install.php?a=compile' );
	}

	//生成完整包
	public function full() {
		$this->version();
		//复制目录
		Dir::copy( '.', $this->path );
		foreach ( $this->filterFullDirectory as $d ) {
			\Dir::del( $this->path . DS . $d );
		}
		//创建压缩包
		exec( "git tag -l", $tags );
		$newVersion = array_pop( $tags );
		chdir( dirname( $this->path ) );
		Zip::PclZip( 'hdcms.full.' . $newVersion . '.zip' );
		Zip::create( 'hdcms' );
		Dir::del( $this->path );
	}

	/**
	 * 生成HDCMS更新压缩包
	 *
	 * @param string $oldVersion 上版本号
	 */
	public function upgrade( $oldVersion = '' ) {
		$this->version();
		chdir( $this->hdcmsDir );
		exec( "git tag -l", $tags );
		$newVersion = array_pop( $tags );
		$oldVersion = $oldVersion ?: array_pop( $tags );
		exec( "git diff $oldVersion $newVersion --name-status ", $files );
		$files = $this->format( $files );
		if ( ! empty( $files ) ) {
			//复制文件
			foreach ( $files as $f ) {
				\Dir::copyFile( $f['file'], $this->path . DS . $f['file'] );
			}
			file_put_contents( $this->path . DS . 'upgrade_files.php',
				"<?php return " . var_export( $files, true ) . ';?>' );
			chdir( dirname( $this->path ) );
			Zip::PclZip( 'hdcms.upgrade.' . $newVersion . '.zip' );
			Zip::create( 'hdcms' );
			Dir::del( $this->path );
		}
	}

	/**
	 * 格式化文件数据
	 * 移除不存在的文件
	 *
	 * @param $files 版本差异中受影响的文件
	 *
	 * @return array
	 */
	protected function format( $files ) {
		//组合后的文件
		$format = [];
		foreach ( $files as $k => $f ) {
			preg_match( '/\w+\s+([^\s]+)/', $f, $file );
			if ( ! in_array( $file[1], $this->filterUpgradeFiles ) ) {
				if ( is_file( $file[1] ) ) {
					if ( in_array( $file[0], [ 'A', 'M' ] ) ) {
						$format[] = [ 'file' => $file[1], 'state' => $file[0] ];
					} else {
						$format[] = [ 'file' => $file[1], 'state' => 'M' ];
					}
				} else {
					$format[] = [ 'file' => $file[1], 'state' => 'D' ];
				}
			}
		}

		return $format;
	}
}