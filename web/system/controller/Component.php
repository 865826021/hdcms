<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\system\controller;

/**
 * 前端组件处理
 * Class component
 * @package system\controller
 * @author 向军
 */
class Component {
	//模块列表
	public function moduleBrowser() {
		if ( ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		View::with( 'modules', v( 'modules' ) );
		View::with( 'useModules', explode( ',', q( 'get.mid', '', [ ] ) ) );
		View::make();
	}

	//加载系统链接
	public function linkBrowser() {
		if ( ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		View::make();
	}

	//模块&模板列表
	public function moduleList() {
		if ( ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		$modules = Db::table( 'modules' )->where( 'is_system', 0 )->get();
		View::with( 'modules', $modules )->make();
	}

	//字体列表
	public function font() {
		if ( ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		View::make();
	}

	//上传图片webuploader
	public function uploader() {
		$uid = Session::get( 'is_member' ) ? Session::get( 'member.uid' ) : Session::get( 'user.uid' );
		if ( empty( $uid ) ) {
			message( '没有操作权限', 'back', 'error' );
		}
		$file = Upload::path( \Config::get( 'upload.path' ) . '/' . date( 'Y/m/d' ) )->make();
		if ( $file ) {
			$data = [
				'uid'        => $uid,
				'siteid'     => SITEID,
				'name'       => $file[0]['name'],
				'filename'   => $file[0]['filename'],
				'path'       => $file[0]['path'],
				'extension'  => strtolower( $file[0]['ext'] ),
				'createtime' => time(),
				'size'       => $file[0]['size'],
				'is_member'  => Session::get( 'is_member' ) ? 1 : 0,
			];
			Db::table( 'core_attachment' )->insert( $data );
			ajax( [ 'valid' => 1, 'message' => $file[0]['path'] ] );
		} else {
			ajax( [ 'valid' => 0, 'message' => \Upload::getError() ] );
		}
	}

	//获取文件列表webuploader
	public function filesLists() {
		$uid       = Session::get( 'is_member' ) ? Session::get( 'member.uid' ) : Session::get( 'user.uid' );
		$is_member = Session::get( 'is_member' ) ? 1 : 0;
		$count     = Db::table( 'core_attachment' )
		               ->where( 'uid', $uid )
		               ->where( 'is_member', $is_member )
		               ->where( 'siteid', SITEID )
		               ->whereIn( 'extension', explode( ',', strtolower( $_GET['extensions'] ) ) )
		               ->count();
		$page      = Page::row( 32 )->pageNum( 8 )->make( $count );
		$data      = Db::table( 'core_attachment' )
		               ->where( 'uid', $uid )
		               ->whereIn( 'extension', explode( ',', strtolower( $_GET['extensions'] ) ) )
		               ->where( 'is_member', $is_member )
		               ->where( 'siteid', SITEID )
		               ->limit( Page::limit() )
		               ->orderBy( 'id', 'DESC' )
		               ->get();
		foreach ( $data as $k => $v ) {
			$data[ $k ]['createtime'] = date( 'Y/m/d', $v['createtime'] );
			$data[ $k ]['size']       = get_size( $v['size'] );
		}
		ajax( [ 'data' => $data, 'page' => $page ] );
	}

	//删除图片delWebuploader
	public function removeImage() {
		if ( ! Session::get( 'member' ) && ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		$db   = Db::table( 'core_attachment' );
		$file = $db->where( 'id', $_POST['id'] )->where( 'siteid', SITEID)->first();
		if ( is_file( $file['path'] ) ) {
			unlink( $file['path'] );
		}
		$db->where( 'id', $_POST['id'] )->where( 'siteid', SITEID)->delete();
	}

	//选择用户
	public function users() {
		if ( ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		if ( isset( $_GET['loadUser'] ) ) {
			//过滤不显示的用户
			$filterUid = explode( ',', q( 'get.filterUid', '' ) );
			$db        = Db::table( 'user' )->join( 'user_group', 'user.groupid', '=', 'user_group.id' );
			//排除站长
			if ( ! empty( $filterUid ) ) {
				$db->whereNotIn( 'uid', $filterUid );
			}
			//按用户名筛选
			if ( empty( $_GET['username'] ) ) {
				$users = $db->get();
			} else {
				$users = $db->where( "username LIKE '%{$_GET['username']}%'" )->get();
			}

			ajax( $users );
		}
		View::make();
	}

	//模块与模板列表,添加站点时选择扩展模块时使用
	public function ajaxModulesTemplate() {
		if ( ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		$modules   = Db::table( 'modules' )->where( 'is_system', 0 )->get();
		$templates = Db::table( 'template' )->get();
		View::with( [
			'modules'   => $modules,
			'templates' => $templates
		] )->make();
	}
	//百度编辑器
	public function ueditor() {
		if ( ! Session::get( 'member' ) && ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		$CONFIG = json_decode( preg_replace( "/\/\*[\s\S]+?\*\//", "", file_get_contents( "config.json" ) ), TRUE );
		$action = $_GET['action'];
		switch ( $action ) {
			case 'config':
				$result = json_encode( $CONFIG );
				break;
			/* 上传图片 */
			case 'uploadimage':
				/* 上传涂鸦 */
			case 'uploadscrawl':
				/* 上传视频 */
			case 'uploadvideo':
				/* 上传文件 */
			case 'uploadfile':
				$result = include( "action_upload.php" );
				break;

			/* 列出图片 */
			case 'listimage':
				$result = include( "action_list.php" );
				break;
			/* 列出文件 */
			case 'listfile':
				$result = include( "action_list.php" );
				break;

			/* 抓取远程文件 */
			case 'catchimage':
				$result = include( "action_crawler.php" );
				break;

			default:
				$result = json_encode( [
					'state' => '请求地址出错'
				] );
				break;
		}
		/* 输出结果 */
		if ( isset( $_GET["callback"] ) ) {
			if ( preg_match( "/^[\w_]+$/", $_GET["callback"] ) ) {
				echo htmlspecialchars( $_GET["callback"] ) . '(' . $result . ')';
			} else {
				echo json_encode( [
					'state' => 'callback参数不合法'
				] );
			}
		} else {
			echo $result;
		}
	}

	//编辑器上传
	public function kindUpload() {
		$uid       = Session::get( 'is_member' ) ? Session::get( 'member.uid' ) : Session::get( 'user.uid' );
		if ( empty($uid)) {
			message( '请登录后操作', 'back', 'error' );
		}
		//文件保存目录路径
		$save_path = \Config::get( 'upload.path' ) . '/keditor/' . ( Session::get( 'is_member' ) ? 'member' : 'admin' ) . '/' . $uid . '/';
		//定义允许上传的文件扩展名
		$ext_arr = [
			'image' => [ 'gif', 'jpg', 'jpeg', 'png', 'bmp' ],
			'flash' => [ 'swf', 'flv' ],
			'media' => [ 'swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb' ],
			'file'  => [ 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2' ],
		];
		$dir     = Q( 'get.dir', 'image' );
		$file    = \Upload::type( $ext_arr[ $dir ] )->path( $save_path . $dir . '/' . date( "Y/m" ) . '/' )->make();
		if ( $file ) {
			$data = [
				'uid'        => $uid,
				'siteid'     => defined( SITEID ) ? SITEID : 0,
				'name'       => $file[0]['name'],
				'filename'   => $file[0]['filename'],
				'path'       => $file[0]['path'],
				'type'       => $file[0]['image'] ? 'image' : 'file',
				'createtime' => time(),
				'size'       => $file[0]['size'],
				'user_type'  => \Session::get( 'user.uid' ) ? 1 : 0
			];
			Db::table( 'core_attachment' )->insert( $data );
			ajax( [ 'error' => 0, 'url' => $file[0]['path'] ] );
		} else {
			ajax( [ 'error' => 1, 'message' => Upload::getError() ] );
		}
	}

	//文件管理
	public function kindFileManagerJson() {
		if ( ! Session::get( 'member' ) && ! Session::get( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		//根目录路径，可以指定绝对路径，比如 /var/www/attached/
		$root_path = ROOT_PATH . '/' . Config::get( 'upload.path' ) . '/keditor/' . $_SESSION['uid'] . '/';
		if ( ! is_dir( $root_path ) ) {
			ajax( [ 'error' => 1, 'message' => 'Directory does not exist.' ] );
		}
		//根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
		$root_url = __ROOT__ . '/' . Config::get( 'upload.path' ) . '/keditor/' . $_SESSION['uid'] . '/';
		//图片扩展名
		$ext_arr = [ 'gif', 'jpg', 'jpeg', 'png', 'bmp' ];

		//目录名
		$dir_name = empty( $_GET['dir'] ) ? '' : trim( $_GET['dir'] );
		if ( ! in_array( $dir_name, [ '', 'image', 'flash', 'media', 'file' ] ) ) {
			echo "Invalid Directory name.";
			exit;
		}
		if ( $dir_name !== '' ) {
			$root_path .= $dir_name . "/";
			$root_url .= $dir_name . "/";
			if ( ! file_exists( $root_path ) ) {
				mkdir( $root_path );
			}
		}
		//根据path参数，设置各路径和URL
		if ( empty( $_GET['path'] ) ) {
			$current_path     = realpath( $root_path ) . '/';
			$current_url      = $root_url;
			$current_dir_path = '';
			$moveup_dir_path  = '';
		} else {
			$current_path     = realpath( $root_path ) . '/' . $_GET['path'];
			$current_url      = $root_url . $_GET['path'];
			$current_dir_path = $_GET['path'];
			$moveup_dir_path  = preg_replace( '/(.*?)[^\/]+\/$/', '$1', $current_dir_path );
		}
		//不允许使用..移动到上一级目录
		if ( preg_match( '/\.\./', $current_path ) ) {
			echo 'Access is not allowed.';
			exit;
		}
		//最后一个字符不是/
		if ( ! preg_match( '/\/$/', $current_path ) ) {
			echo 'Parameter is not valid.';
			exit;
		}
		//目录不存在或不是目录
		if ( ! file_exists( $current_path ) || ! is_dir( $current_path ) ) {
			echo 'Directory does not exist.';
			exit;
		}
		//遍历目录取得文件信息
		$file_list = [ ];
		if ( $handle = opendir( $current_path ) ) {
			$i = 0;
			while ( FALSE !== ( $filename = readdir( $handle ) ) ) {
				if ( $filename{0} == '.' ) {
					continue;
				}
				$file = $current_path . $filename;
				if ( is_dir( $file ) ) {
					$file_list[ $i ]['is_dir']   = TRUE; //是否文件夹
					$file_list[ $i ]['has_file'] = ( count( scandir( $file ) ) > 2 ); //文件夹是否包含文件
					$file_list[ $i ]['filesize'] = 0; //文件大小
					$file_list[ $i ]['is_photo'] = FALSE; //是否图片
					$file_list[ $i ]['filetype'] = ''; //文件类别，用扩展名判断
				} else {
					$file_list[ $i ]['is_dir']   = FALSE;
					$file_list[ $i ]['has_file'] = FALSE;
					$file_list[ $i ]['filesize'] = filesize( $file );
					$file_list[ $i ]['dir_path'] = '';
					$file_ext                    = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
					$file_list[ $i ]['is_photo'] = in_array( $file_ext, $ext_arr );
					$file_list[ $i ]['filetype'] = $file_ext;
				}
				$file_list[ $i ]['filename'] = $filename; //文件名，包含扩展名
				$file_list[ $i ]['datetime'] = date( 'Y-m-d H:i:s', filemtime( $file ) ); //文件最后修改时间
				$i ++;
			}
			closedir( $handle );
		}

		//排序
		function cmp_func( $a, $b ) {
			if ( $a['is_dir'] && ! $b['is_dir'] ) {
				return - 1;
			} else if ( ! $a['is_dir'] && $b['is_dir'] ) {
				return 1;
			} else {
				return strcmp( $a['filename'], $b['filename'] );
			}
		}

		usort( $file_list, 'cmp_func' );
		$result = [ ];
		//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
		//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
		//当前目录的URL
		$result['current_url'] = $current_url;
		//文件数
		$result['total_count'] = count( $file_list );
		//文件列表数组
		$result['file_list'] = $file_list;
		//输出JSON字符串
		ajax( $result );
	}

}