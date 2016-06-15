<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace api;

//官网管理
class web {

	/**
	 * 添加/编辑微站
	 *
	 * @param $web
	 *
	 * @return mixed 微站编号
	 */
	public function post( $web ) {
		$web['siteid'] = v( "site.siteid" );
		if ( ! empty( $web['id'] ) && $web['id'] > 0 ) {
			Db::table( 'web' )->where( 'id', $web['id'] )->delete();
		}

		return Db::table( 'web' )->replaceGetId( $web );
	}

	/**
	 * 检测当前站点中是否存在官网
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function has( $id ) {
		return Db::table( 'web' )->where( 'siteid', v( 'site.siteid' ) )->where( 'id', $id )->first() ? TRUE : FALSE;
	}

	/**
	 * 获取站点的模板数据
	 *
	 * @param $web_id 站点编号
	 *
	 * @return array 模板数据
	 */
	public function getTemplateData( $webid ) {
		$template_tid = Db::table( 'web' )->where( 'id', $webid )->pluck( 'template_tid' );

		return Db::table( 'template' )->where( 'tid', $template_tid )->first();
	}

	/**
	 * 获取站点的所有官网数据
	 * @return array
	 */
	public function getSiteWebs() {
		$web = Db::table( 'web' )->where( 'siteid', v( 'site.siteid' ) )->orderBy( 'id', 'asc' )->get();

		return Arr::string_to_int( $web );
	}

	/**
	 * 获取默认站点
	 * @return array
	 */
	public function getDefaultWeb() {
		return Db::table( 'web' )->where( 'siteid', v( 'site.siteid' ) )->orderBy( 'id', 'asc' )->first();
	}

}