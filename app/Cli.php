<?php namespace app;

use houdunwang\cli\build\Base;

/**
 * 命令处理
 * Class Cms
 * @package app
 */
class Cli extends Base {
	//动作
	public function upgrade() {
		$files = preg_split( '@\n@', file_get_contents( 'build/files.php' ) );
		foreach ( $files as $f ) {
			$info = preg_split( '@\s+@', trim( $f ) );
			//添加修改时收集更新文件
			if ( in_array( $info[0], [ 'A', 'M' ] ) ) {
				\Dir::copyFile( $info[1], 'build/hdcms/' . $info[1] );
			}
		}
		chdir( 'build' );
		Zip::PclZip( 'hdcms.zip' );
		Zip::create( 'hdcms' );
		copy( 'hdcms.zip', '../hdcms.zip' );
		chdir( '..' );
	}
}