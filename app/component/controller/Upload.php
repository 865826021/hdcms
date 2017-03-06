<?php namespace app\component\controller;

use houdunwang\request\Request;

/**
 * 上传处理
 * Class Upload
 * @package app\component\controller
 */
class Upload extends Common {
	public function __construct() {
		$this->auth();
	}

	//上传图片webuploader
	public function uploader() {
		//中间件
		\Middleware::exe( 'upload_begin' );
		$file = \File::path( c( 'upload.path' ) . '/' . date( 'Y/m/d' ) )->path( Request::post( 'uploadDir', 'attachment' ) )->upload();
		if ( $file ) {
			$data = [
				'uid'        => v( Request::post( 'user_type' ) . '.info.uid' ),
				'siteid'     => SITEID,
				'name'       => $file[0]['name'],
				'filename'   => $file[0]['filename'],
				'path'       => $file[0]['path'],
				'extension'  => strtolower( $file[0]['ext'] ),
				'createtime' => time(),
				'size'       => $file[0]['size'],
				'status'     => 0
			];
			$data = array_merge( $data, Request::post(), [ ] );
			Db::table( 'attachment' )->insert( $data );
			ajax( [ 'valid' => 1, 'message' => $file[0]['path'] ] );
		} else {
			ajax( [ 'valid' => 0, 'message' => \File::getError() ] );
		}
	}

	//获取文件列表webuploader
	public function filesLists() {
		$db = Db::table( 'attachment' )
		        ->where( 'uid', v( Request::post( 'user_type' ) . '.info.uid' ) )
		        ->whereIn( 'extension', explode( ',', strtolower( Request::post( 'extensions' ) ) ) )
		        ->where( 'user_type', Request::post( 'user_type', 'member' ) )
		        ->orderBy( 'id', 'DESC' );
		if ( Request::post( 'user_type' ) != 'user' ) {
			//前台会员根据站点编号读取数据
			$db->where( 'siteid', SITEID );
		}
		$Res  = $db->paginate( 32 );
		$data = [ ];
		if ( $Res->toArray() ) {
			foreach ( $Res as $k => $v ) {
				$data[ $k ]['createtime'] = date( 'Y/m/d', $v['createtime'] );
				$data[ $k ]['size']       = \Tool::getSize( $v['size'] );
				$data[ $k ]['url']        = __ROOT__ . '/' . $v['path'];
				$data[ $k ]['path']       = $v['path'];
				$data[ $k ]['name']       = $v['name'];
			}
		}
		ajax( [ 'data' => $data, 'page' => $Res->links() ] );
	}

	//删除图片delWebuploader
	public function removeImage() {
		$db   = Db::table( 'attachment' );
		$file = $db->where( 'id', $_POST['id'] )->where( 'uid', v( 'user.info.uid' ) )->first();
		if ( is_file( $file['path'] ) ) {
			unlink( $file['path'] );
		}
		$db->where( 'id', $_POST['id'] )->where( 'uid', v( 'user.info.uid' ) )->delete();
	}
}