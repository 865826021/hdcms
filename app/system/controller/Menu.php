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

use system\model\User;

/**
 * 菜单管理
 * Class Menu
 * @package core\controller
 * @author 向军
 */
class Menu {
	protected $user;
	protected $menu;

	public function __construct() {
		$this->user = new User();
		$this->menu = new \system\model\Menu();
		$this->user->isSuperUser( v( "user.uid" ) );
	}

	//编辑菜单
	public function edit() {
		if ( IS_POST ) {
			$menu = json_decode( Request::post( 'menu' ) );
			foreach ( $menu as $m ) {
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
				if ( $d['mark'] && $d['title'] ) {
					$this->menu->replace( $d );
				}
			}
			message( '菜单更改成功' );
		}
		$data  = $this->menu->get()->toArray();
		$menus = Data::tree( $data, 'title', 'id', 'pid' );

		return view()->with( 'menus', json_encode( $menus, JSON_UNESCAPED_UNICODE ) );
	}

	//更改显示状态
	public function changeDisplayState() {
		$this->menu->id         = Request::post( 'id' );
		$this->menu->is_display = Request::post( 'is_display' );
		$this->menu->save();
		ajax( [ 'valid' => TRUE, 'message' => '菜单更改成功' ] );
	}

	//删除菜单
	public function delMenu() {
		$data         = Db::table( 'menu' )->get();
		$menu         = Data::channelList( $data, $_POST['id'], "&nbsp;", 'id', 'pid' );
		$menu[]['id'] = $_POST['id'];
		foreach ( $menu as $m ) {
			$this->menu->where( 'id', $m['id'] )->delete();
		}
		ajax( [ 'valid' => TRUE, 'message' => '删除成功' ] );
	}

}