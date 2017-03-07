<?php namespace app;

use houdunwang\cli\build\Base;

/**
 * 命令处理
 * Class Cms
 * @package app
 */
class Cli extends Base {
	//不生成到压缩包的文件
	protected $filterFiles = [
		'data/database.php'
	];

	/**
	 * 生成HDCMS更新压缩包
	 *
	 * @param $old 上版本号
	 * @param $new 新版本号
	 */
	public function upgrade( $old, $new ) {
		exec( "git tag -l", $tags );
		$newVersion = array_pop( $tags );
		$oldVersion = array_pop( $tags );
		exec( "git diff $oldVersion $newVersion --name-status ", $files );
		$files = $this->format( $files );
		if ( ! empty( $files ) ) {
			//复制文件
			foreach ( $files as $f ) {
				\Dir::copyFile( $f['file'], '/Users/xj/Desktop/build/hdcms/' . $f['file'] );
			}
			file_put_contents( '/Users/xj/Desktop/build/hdcms/upgrade_files.php', "<?php return " . var_export( $files, true ) . ';?>' );
			chdir( '/Users/xj/Desktop/build' );
			Zip::PclZip( 'hdcms.zip' );
			Zip::create( 'hdcms' );
			copy( 'hdcms.zip', '/Users/xj/Desktop/hdcms.zip' );
			chdir( '..' );
		}
		\Dir::del( '/Users/xj/Desktop/build' );
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
		$format = [ ];
		foreach ( $files as $k => $f ) {
			preg_match( '/\w+\s+([^\s]+)/', $f, $file );
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

		return $format;
	}
}