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
use system\model\Menu as MenuModel;

/**
 * 菜单管理
 * Class Menu
 * @package core\controller
 * @author 向军
 */
class Menu {
	public function __construct() {
		\User::superUserAuth();
	}

	//编辑菜单
	public function edit() {
		if ( IS_POST ) {
			$data = json_decode( Request::post( 'menu' ) );
			foreach ( $data as $m ) {
				$model = empty( $m->id ) ? new MenuModel() : MenuModel::find( $m->id );
				$model['pid']        = intval( $m->pid );
				$model['title']      = $m->title;
				$model['permission'] = $m->permission;
				$model['url']        = $m->url;
				$model['append_url'] = $m->append_url;
				$model['icon']       = $m->icon;
				$model['orderby']    = intval( $m->orderby );
				$model['is_display'] = $m->is_display;
				$model['mark']       = $m->mark;
				$model['is_system']  = $m->is_system;
				$model->save();
			}
			message( '菜单列表更新成功', 'with' );
		}
		$data  = MenuModel::get()->toArray();
		$menus = \Arr::tree( $data, 'title', 'id', 'pid' );

		return view()->with( 'menus', json_encode( $menus, JSON_UNESCAPED_UNICODE ) );
	}

	/**
	 * 更改显示状态
	 */
	public function changeDisplayState() {
		$model               = MenuModel::find( q( 'post.id' ) );
		$model['id']         = Request::post( 'id' );
		$model['is_display'] = Request::post( 'is_display' );
		$model->save();
		message( '菜单显示状态更改成功' );
	}

	/**
	 * 删除菜单
	 */
	public function delMenu() {
		$id = Request::post( 'id' );
		if ( MenuModel::where( 'id', $id )->where( 'is_system', 1 )->get() ) {
			message( '系统菜单不允许删除', 'with' );
		}
		$data         = MenuModel::get();
		$menu         = \Arr::channelList( $data, $id, "&nbsp;", 'id', 'pid' );
		$menu[]['id'] = $id;
		foreach ( $menu as $m ) {
			MenuModel::where( 'id', $m['id'] )->delete();
		}
		message( '菜单删除成功' );
	}

}