<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace module\quickmenu\controller;

use module\HdController;

/**
 * 底部快捷导航
 * Class Site
 * @package module\quickmenu\controller
 */
class Site extends HdController {
	/**
	 * 添加快捷菜单
	 */
	public function post() {
		auth();
		$data = Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->where( 'uid', v( 'user.info.uid' ) )->pluck( 'data' );
		$data = $data ? json_decode( $data, true ) : [
			'system' => [ ],
			'module' => [ ]
		];
		$post = Request::post();
		if ( isset( $post['module'] ) ) {
			//当前模块数据
			$module = v( 'site.modules.' . $post['module'] );
			//模块菜单,原来没有添加时初始模块菜单数据
			if ( ! isset( $data['module'][ $module['name'] ] ) ) {
				$data['module'][ $module['name'] ] = [ 'title' => $module['title'], 'action' => [ ] ];
			}
			//检测链接是否已经存在,如果存在时不处理
			foreach ( $data['module'][ $module['name'] ]['action'] as $a ) {
				if ( $a['url'] == $post['url'] ) {
					message( '菜单添加成功', '', 'success' );
				}
			}
			array_push( $data['module'][ $module['name'] ]['action'], [
				'title' => preg_replace( '/\s/s', '', $post['title'] ),
				'url'   => preg_replace( '/\s/s', '', $post['url'] ),
			] );
		} else {
			//系统菜单,首先检测菜单是否已经存了,存在了就不添加了
			foreach ( $data['system'] as $a ) {
				if ( $a['url'] == $post['url'] ) {
					message( '菜单添加成功', '', 'success' );
				}
			}
			array_push( $data['system'], [
				'title' => preg_replace( '/\s/s', '', $post['title'] ),
				'url'   => preg_replace( '/\s/s', '', $post['url'] ),
			] );
		}
		$id = Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->pluck( 'id' );
		if ( $id ) {
			$insertData['id'] = $id;
		}
		$insertData['siteid'] = siteid();
		$insertData['uid']    = v( 'user.info.uid' );
		$insertData['data']   = json_encode( $data, JSON_UNESCAPED_UNICODE );
		Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->replace( $insertData );
		message( '菜单添加成功', '', 'success' );
	}

	/**
	 * 关闭底部菜单
	 */
	public function status() {
		auth();
		if ( IS_POST ) {
			$data = Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->where( 'uid', v( 'user.info.uid' ) )->pluck( 'data' );
			$data = $data ? json_decode( $data, true ) : [
				'status' => 0,
				'system' => [ ],
				'module' => [ ]
			];
			$id   = Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->pluck( 'id' );
			if ( $id ) {
				$insertData['id'] = $id;
			}
			$data['status']       = intval( Request::post( 'status' ) );
			$insertData['siteid'] = siteid();
			$insertData['uid']    = v( 'user.info.uid' );
			$insertData['data']   = json_encode( $data, JSON_UNESCAPED_UNICODE );
			Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->replace( $insertData );
			message( '菜单修改成功', '', 'success' );
		}
		$data = Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->where( 'uid', v( 'user.info.uid' ) )->pluck( 'data' );
		$data = $data ? json_decode( $data, true ) : [ 'status' => 0 ];

		return view( $this->template . '/status.html' )->with( 'status', $data['status'] );
	}

	public function del() {
		$menu = Db::table( 'site_quickmenu' )->where( 'siteid', siteid() )->where( 'uid', v( 'user.info.uid' ) )->first();
		$tmp  = $data = json_decode( $menu['data'], true );
		//从系统菜单删除
		foreach ( $data['system'] as $k => $d ) {
			if ( $d['url'] == $_POST['url'] ) {
				unset( $tmp['system'][ $k ] );
				break;
			}
		}
		//从模块菜单删除
		foreach ( $data['module'] as $moduleName => $d ) {
			foreach ( $d['action'] as $n => $m ) {
				if ( $m['url'] == $_POST['url'] ) {
					unset( $tmp['module'][ $moduleName ]['action'][ $n ] );
					//模块没动作时,删除这个模块的菜单列表
					if ( empty( $tmp['module'][ $moduleName ]['action'] ) ) {
						unset( $tmp['module'][ $moduleName ] );
					}
					break 2;
				}
			}
		}
		$insertData['data'] = json_encode( $tmp, JSON_UNESCAPED_UNICODE );
		Db::table( 'site_quickmenu' )->where( 'id', $menu['id'] )->update( $insertData );
		message( '删除菜单成功', 'back', 'success' );
	}
}