<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace app\system\controller;
use houdunwang\request\Request;

/**
 * 前端组件处理
 * Class component
 * @package system\controller
 * @author 向军
 */
class Component {
	//模块列表
	public function moduleBrowser() {
		\User::loginAuth();
		View::with( 'modules', v( 'site.modules' ) );
		View::with( 'useModules', explode( ',', q( 'get.mid', '', [ ] ) ) );

		return view();
	}

	//选择站点模板模板
	public function siteTemplateBrowser() {
		\User::loginAuth();
		$data = \Template::getSiteAllTemplate();

		return view()->with( 'data', $data );
	}

	//模块列表
	public function moduleList() {
		\User::loginAuth();
		$modules = Db::table( 'modules' )->get();

		return view()->with( 'modules', $modules );
	}

	//字体列表
	public function font() {
		if ( ! v( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}

		return View::make();
	}

	//上传图片webuploader
	public function uploader() {
		if ( ! v( 'user' ) && ! v( 'member' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		if ( ! Request::post( 'user_type' ) ) {
			message( '请设置user_type' );
		}
		$file = \File::path( c( 'upload.path' ) . '/' . date( 'Y/m/d' ) )->dir(Request::post('uploadDir','attachment'))->upload();
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
				'status'     => 1
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
		if ( ! v( 'user' ) && ! v( 'member' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
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
		if ( ! v( 'user' ) ) {
			message( '请登录后操作', 'back', 'error' );
		}
		$db   = Db::table( 'attachment' );
		$file = $db->where( 'id', $_POST['id'] )->where( 'uid', v( 'user.info.uid' ) )->first();
		if ( is_file( $file['path'] ) ) {
			unlink( $file['path'] );
		}
		$db->where( 'id', $_POST['id'] )->where( 'uid', v( 'user.info.uid' ) )->delete();
	}

	//选择用户
	public function users() {
		\User::loginAuth();
		if ( IS_POST ) {
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

		return view();
	}

	//模块与模板列表,添加站点时选择扩展模块时使用
	public function ajaxModulesTemplate() {
		\User::loginAuth();
		$modules   = Db::table( 'modules' )->where( 'is_system', 0 )->get();
		$templates = Db::table( 'template' )->where( 'is_system', 0 )->get();

		return view()->with( [
			'modules'   => $modules,
			'templates' => $templates
		] );
	}
}