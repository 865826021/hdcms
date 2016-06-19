<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\site\controller;

/**
 * 导航菜单设置
 * Class Nav
 * @package web\site\controller
 * @author 向军
 */
class Nav {
	protected $webid;

	public function __construct() {
		//分配菜单
		api( 'menu' )->assignMenus();
		//站点编号
		$this->webid = q( 'get.webid', 0, 'intval' );
		if ( $this->webid && ! Db::table( 'web' )->find( $this->webid ) ) {
			message( '你访问的站点不存在', 'back', 'error' );
		}
	}

	//菜单列表管理
	public function lists() {
		if ( IS_POST ) {
			$data = json_decode( $_POST['data'], TRUE );
			foreach ( (array) $data as $d ) {
				$d['css']     = json_encode( $d['css'] );
				$d['orderby'] = min( 255, intval( $d['orderby'] ) );
				Db::table( 'web_nav' )->where( 'id', $d['id'] )->update( $d );
			}
			message( '保存导航数据成功', 'refresh', 'success' );
		}
		//当前显示导航的站点
		if ( ! $this->webid ) {
			$web         = api( 'web' )->getDefaultWeb();
			$this->webid = $web['id'];
		}
		//当前站点模板数据
		$template = api( 'web' )->getTemplateData( $this->webid );
		//获取导航菜单,entry是导航类型 home 微站首页导航  profile 手机会员中心导航 member 桌面会员中心导航  profile本类不进行处理
		$nav = Db::table( 'web_nav' )->where( 'web_id', $this->webid )->where( 'entry', q( 'get.entry' ) )->get();
		if ( $nav ) {
			$nav = Arr::string_to_int( $nav );
			foreach ( $nav as $k => $v ) {
				$nav[ $k ]['css'] = ( json_decode( $v['css'], TRUE ) );
			}
		}
		View::with( 'web', api( 'web' )->getSiteWebs() );
		View::with( 'webid', $this->webid );
		View::with( 'nav', $nav );
		View::with( 'template', $template );
		View::with( 'template_position_data', api( 'template' )->getPositionData( $template['tid'] ) );
		View::make();
	}

	//更改导航状态
	public function changeStatus() {
		$data['id']     = $_POST['data']['id'];
		$data['status'] = $_POST['data']['status'];
		Db::table( 'web_nav' )->where( 'id', $data['id'] )->update( $data );
	}

	//添加&修改
	public function post() {
		if ( IS_POST ) {
			$data                 = json_decode( $_POST['data'], TRUE );
			$data['position']     = intval( $data['position'] );
			$data['orderby']      = min( intval( $data['orderby'] ), 256 );
			$data['css']['size']  = min( intval( $data['css']['size'] ), 100 );
			$data['css']          = json_encode( $data['css'] );
			$data['entry']        = q( 'get.entry', 0, 'strtolower' );
			$data['module']       = empty( $data['module'] ) ? '' : $data['module'];
			$data['description']  = empty( $data['description'] ) ? '' : $data['description'];
			$data['category_cid'] = empty( $data['category_cid'] ) ? 0 : $data['category_cid'];
			$id                   = Db::table( 'web_nav' )->replaceGetId( $data );
			$nav                  = Db::table( 'web_nav' )->find( $id );
			message( '保存导航数据成功', u( 'lists', [ 'webid' => $nav['web_id'], 'entry' => $nav['entry'] ] ), 'success' );
		}
		//站点列表
		$web          = Db::table( 'web' )
		                  ->field( 'web.id,web.title,template.tid,template.position' )
		                  ->join( 'template', 'template.tid', '=', 'web.template_tid' )
		                  ->where( 'siteid', v( 'site.siteid' ) )
		                  ->get();
		$field        = Db::table( 'web_nav' )->where( 'id', q( 'get.id' ) )->first() ?: [ ];
		$field['css'] = empty( $field['css'] ) ? [ ] : json_decode( $field['css'], TRUE );
		//新增数据时初始化导航数据,只有通过官网添加导航链接才有效,模块的只有编辑操作,所以模块一定有数据
		if ( empty( $field['id'] ) ) {
			$field['siteid']   = v( "site.siteid" );
			$field['position'] = 0;
			$field['icontype'] = 1;
			$field['status']   = 1;
			$field['orderby']  = 0;
			$field['entry']    = 'home';
			$field['css']      = [ 'icon' => 'fa fa-external-link', 'image' => '', 'color' => '#333333', 'size' => 35 ];
			$cur               = current( $web );
			$field['web_id']   = $cur['id'];
		}
		View::with( 'web', Arr::string_to_int( $web ) );
		View::with( 'field', Arr::string_to_int( $field ) );
		View::make();
	}

	//删除菜单
	public function del() {
		Db::table( 'web_nav' )->where( 'id', '=', q( 'get.id' ) )->delete();
		message( '菜单删除成功', 'back', 'success' );
	}
}