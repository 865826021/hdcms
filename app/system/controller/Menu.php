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

/**
 * 菜单管理
 * Class Menu
 * @package core\controller
 * @author 向军
 */
class Menu {
	public function __construct() {
		if ( ! service( 'user' )->isSuperUser() ) {
			message( '您不是系统管理员无法进行操作', 'back', 'error' );
		}
	}

	//编辑菜单
	public function edit() {
		$menu = new \system\model\Menu();
		if ( IS_POST ) {
			$data = json_decode( Request::post( 'menu' ) );
			foreach ( $data as $m ) {
				$d = [ ];
				if ( ! empty( $m->id ) ) {
					$d['id'] = $m->id;
				}
				$d['pid']        = intval( $m->pid );
				$d['title']      = $m->title;
				$d['permission'] = $m->permission;
				$d['url']        = $m->url;
				$d['append_url'] = $m->append_url;
				$d['icon']       = $m->icon;
				$d['orderby']    = intval( $m->orderby );
				$d['is_display'] = $m->is_display;
				$d['mark']       = $m->mark;
				$d['is_system']  = $m->is_system;
				if ( $d['mark'] && $d['title'] ) {
					$menu->replace( $d );
				}
			}
			message( '菜单更改成功' );
		}
		$data  = $menu->get()->toArray();
		$menus = Data::tree( $data, 'title', 'id', 'pid' );

		return view()->with( 'menus', json_encode( $menus, JSON_UNESCAPED_UNICODE ) );
	}

	/**
	 * 更改显示状态
	 */
	public function changeDisplayState() {
		$menu             = new \system\model\Menu();
		$menu->id         = Request::post( 'id' );
		$menu->is_display = Request::post( 'is_display' );
		$menu->save();
		ajax( [ 'valid' => TRUE, 'message' => '菜单更改成功' ] );
	}

	/**
	 * 删除菜单
	 */
	public function delMenu() {
		$db = new \system\model\Menu();
		$id = Request::post( 'id' );
		if ( $db->where( 'id', $id )->where( 'is_system', 1 )->get() ) {
			message( '系统菜单不允许删除', 'back', 'error' );
		}
		$data         = Db::table( 'menu' )->get();
		$menu         = Data::channelList( $data, $_POST['id'], "&nbsp;", 'id', 'pid' );
		$menu[]['id'] = $id;
		foreach ( $menu as $m ) {
			$db->where( 'id', $m['id'] )->delete();
		}
		ajax( [ 'valid' => TRUE, 'message' => '删除成功' ] );
	}

}