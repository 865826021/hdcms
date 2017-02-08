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
		$files = $this->format();
		foreach ( $files as $f ) {
			if ( in_array( $f[0], [ 'A', 'M' ] ) ) {
				$info = preg_split( '@\s+@', trim( $f ) );
				\Dir::copyFile( $info[1], 'build/hdcms/' . $info[1] );
			}
		}
		file_put_contents( 'build/hdcms/upgrade_files.php', implode( "\n", $files ) );
		chdir( 'build' );
		Zip::PclZip( 'hdcms.zip' );
		Zip::create( 'hdcms' );
		copy( 'hdcms.zip', '../hdcms.zip' );
		chdir( '..' );
		\Dir::del( 'build' );
		@unlink( 'files.php' );
	}

	/**
	 * 格式化文件数据
	 * 将R替换类型进行处理
	 * @return array
	 */
	protected function format() {
		$news = $files = preg_split( '@\n@', file_get_contents( 'files.php' ) );
		foreach ( $files as $k => $f ) {
			if ( empty( $f ) ) {
				unset( $news[ $k ] );
			} elseif ( $f[0] == 'R' ) {
				$info = preg_split( '@\s+@', trim( $f ) );
				unset( $news[ $k ] );
				$news[] = "D\t{$info[1]}";
				$news[] = "A\t{$info[2]}";
			}
		}

		return $news;
	}
}