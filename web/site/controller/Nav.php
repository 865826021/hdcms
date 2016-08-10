<?php namespace web\site\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\Menu;
use system\model\Template;
use system\model\Web;
use system\model\WebNav;

/**
 * 导航菜单设置
 * Class Nav
 * @package web\site\controller
 * @author 向军
 */
class Nav {
	protected $webid;
	protected $web;
	protected $webNav;
	protected $id;

	public function __construct() {
		if ( ! Session::get( 'user.uid' ) ) {
			message( '请登录后操作', 'system/entry/login', 'error' );
		}
		//分配菜单
		( new Menu() )->getMenus( TRUE );
		//站点编号
		$this->webid  = q( 'get.webid', 0, 'intval' );
		$this->web    = new Web();
		$this->webNav = new WebNav();
		$this->id     = q( 'get.id', 0, 'intval' );
		if ( $this->webid && ! Db::table( 'web' )->where( 'siteid', SITEID )->where( 'id', $this->webid )->get() ) {
			message( '你访问的站点不存在', 'back', 'error' );
		}
		if ( $this->id && ! $this->webNav->where( 'siteid', SITEID )->where( 'id', $this->id )->get() ) {
			message( '导航不存在或不属于这个站点', 'back', 'error' );
		}
	}

	//菜单列表管理
	public function lists() {
		if ( IS_POST ) {
			$data = json_decode( $_POST['data'], TRUE );
			foreach ( (array) $data as $d ) {
				$action = empty( $d['id'] ) ? 'add' : 'save';
				if ( ! $this->webNav->$action( $d ) ) {
					message( $this->webNav->getError(), 'refresh', 'error' );
				}
			}
			message( '保存导航数据成功', 'refresh', 'success' );
		}
		//当前显示导航的站点
		if ( ! $this->webid ) {
			if ( $web = ( new Web() )->getDefaultWeb() ) {
				$this->webid = $_GET['webid'] = $web['id'];
			}
		}
		//当前站点模板数据
		$template = $this->web->getTemplateData( $this->webid );
		//获取导航菜单,entry是导航类型 home 微站首页导航  profile 手机会员中心导航 member 桌面会员中心导航  profile本类不进行处理
		if ( v( 'module.name' ) ) {
			//模块菜单
			$nav = $this->webNav->where( 'web_id', $this->webid )->where( 'entry', q( 'get.entry' ) )->where( 'module', v( 'module.name' ) )->get();
			//从模块动作中移除已经在菜单中有值的菜单
			$modulesBindings = Db::table( 'modules_bindings' )->where( 'module', v( 'module.name' ) )->where( 'entry', q( 'get.entry' ) )->get();
			foreach ( $modulesBindings as $k => $v ) {
				$modulesBindings[ $k ]['url'] = "?a={$v['module']}/site/{$v['do']}&t=web&siteid=" . SITEID . '&m=' . v( 'module.name' );
				foreach ( $nav as $n ) {
					if ( strstr( $n['url'], $modulesBindings[ $k ]['url'] ) ) {
						unset( $modulesBindings[ $k ] );
					}
				}
			}
			foreach ( $modulesBindings as $v ) {
				$nav[] = [
					'web_id'   => $this->webid,
					'module'   => $v['module'],
					'url'      => $v['url'],
					'position' => 0,
					'name'     => $v['title'],
					'css'      => json_encode( [
						'icon'  => 'fa fa-external-link',
						'image' => '',
						'color' => '#333333',
						'size'  => 35,
					] ),
					'orderby'  => 0,
					'status'   => 0,
					'icontype' => 1,
					'entry'    => q( 'get.entry' ),
				];
			}
		} else {
			//系统菜单
			$nav = $this->webNav->where( 'web_id', $this->webid )->where( 'entry', q( 'get.entry' ) )->get();
		}
		if ( $nav ) {
			$nav = Arr::string_to_int( $nav );
			foreach ( $nav as $k => $v ) {
				$nav[ $k ]['css'] = json_decode( $v['css'], TRUE );
			}
		}
		//模块时将模块菜单添加进去
		View::with( 'web', $this->web->getSiteWebs() );
		View::with( 'webid', $this->webid );
		View::with( 'nav', $nav );
		View::with( 'template', $template );
		View::with( 'template_position_data', ( new Template() )->getPositionData( $template['tid'] ) );
		View::make();
	}

	//更改导航状态
	public function changeStatus() {
		$action = empty( $_POST['data']['id'] ) ? 'add' : 'save';
		if ( ! $this->webNav->$action( $_POST['data'] ) ) {
			message( $this->webNav->getError(), 'back', 'error' );
		}
		message( '更改状态成功', 'back', 'success' );
	}

	//添加&修改
	public function post() {
		if ( IS_POST ) {
			$data                = json_decode( $_POST['data'], TRUE );
			$data['css']['size'] = min( intval( $data['css']['size'] ), 100 );
			$action              = $this->id ? 'save' : 'add';
			if ( ! $this->webNav->$action( $data ) ) {
				message( $this->webNav->getError(), 'back', 'error' );
			}
			message( '保存导航数据成功', $_POST['__HISTORY__'], 'success' );
		}
		//站点列表
		$web          = Db::table( 'web' )
		                  ->field( 'web.id,web.title,template.tid,template.position' )
		                  ->join( 'template', 'template.name', '=', 'web.template_name' )
		                  ->where( 'siteid', SITEID )
		                  ->get();
		$field        = $this->webNav->where( 'id', $this->id )->first();
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
			$field['web_id']   = $_GET['webid'];
		}
		View::with( 'web', Arr::string_to_int( $web ) );
		View::with( 'field', Arr::string_to_int( $field ) );
		View::make();
	}

	//删除菜单
	public function del() {
		$this->webNav->delete( q( 'get.id' ) );
		message( '菜单删除成功', 'back', 'success' );
	}
}