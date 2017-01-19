<?php namespace module\navigate\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use houdunwang\request\Request;
use module\HdController;
use system\model\Navigate as NavigateModel;

/**
 * 导航菜单管理
 * Class Nav
 * @package web\site\controller
 * @author 向军
 */
class Navigate extends HdController {
	//官网编号
	protected $webid;
	//菜单编号
	protected $id;

	public function __construct() {
		parent::__construct();
		//验证操作员权限
		\User::isOperate();
		$this->webid  = Request::get( 'webid' );
		$this->id     = Request::get( 'id' );
		/**
		 * 没有站点编号时设置站点编号
		 * 在前台添加菜单时使用
		 * 因为添加菜单只能给微站首页添加必须有这个字段
		 */
		if ( empty( $this->webid ) || ! \Web::has( $this->webid ) ) {
			$this->webid = Db::table( 'web' )->where( 'siteid', SITEID )->pluck( 'id' );
		}
		if ( $this->id && ! NavigateModel::where( 'siteid', SITEID )->where( 'id', $this->id )->get() ) {
			message( '导航不存在或不属于这个站点', 'back', 'error' );
		}
	}

	//菜单列表管理
	public function lists() {
		if ( IS_POST ) {
			$data = json_decode( Request::post( 'data' ), true );
			foreach ( $data as $k => $nav ) {
				$model = NavigateModel::find( $nav['id'] );
				$model->save( $nav );
			}
			message( '保存导航数据成功', 'refresh', 'success' );
		}
		//当前显示导航的站点
		if ( ! $this->webid ) {
			if ( $web = \Web::getDefaultWeb() ) {
				$this->webid = $_GET['webid'] = $web['id'];
			}
		}
		//当前站点模板数据
		$template = \Template::getTemplateData( $this->webid );
		//获取导航菜单,entry是导航类型 home 微站首页导航  profile 手机会员中心导航 member 桌面会员中心导航  profile本类不进行处理
		if ( Request::get( 'entry' ) == 'home' ) {
			/**
			 * 站点菜单即类型为home
			 * 不是模块会员中心菜单或桌面会员中心菜单
			 */
			$nav = Db::table( 'navigate' )->where( 'webid', $this->webid )->where( 'entry', 'home' )->get();
		} else {
			//模块菜单
			$webNav = Db::table( 'web_nav' )
			            ->where( 'webid', $this->webid )
			            ->where( 'entry', q( 'get.entry' ) )
			            ->where( 'module', v( 'module.name' ) )
			            ->get() ?: [ ];
			//从模块动作中移除已经在菜单中存在的的菜单
			$modulesBindings = Db::table( 'modules_bindings' )->where( 'module', $this->module )->where( 'entry', q( 'get.entry' ) )->get();
			foreach ( $modulesBindings as $k => $v ) {
				$modulesBindings[ $k ]['url'] = "?m={$v['module']}&action=menu/{$v['do']}&siteid=" . SITEID;
				foreach ( $webNav as $n ) {
					if ( $n['url'] == $modulesBindings[ $k ]['url'] ) {
						unset( $modulesBindings[ $k ] );
					}
				}
			}
			foreach ( $modulesBindings as $v ) {
				$nav[] = [
					'webid'    => $this->webid,
					'module'   => $this->module,
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
		}
		/**
		 * 编辑菜单时
		 * 将菜单数组转为JSON格式为前台使用
		 */
		if ( $nav ) {
			foreach ( $nav as $k => $v ) {
				$nav[ $k ]['css'] = json_decode( $v['css'], true );
			}
		}
		//模块时将模块菜单添加进去
		View::with( 'web', Arr::stringToInt( \Web::getSiteWebs() ) );
		View::with( 'webid', $this->webid );
		View::with( 'nav', Arr::stringToInt( $nav ) );
		View::with( 'template', $template );
		View::with( 'template_position_data', \Template::getPositionData( $template['tid'] ) );
		return view( $this->template . '/lists.html' );
	}

	/**
	 * 添加&修改菜单
	 * 只对微站首页菜单管理
	 * 即entry类型为home的菜单
	 * @return mixed
	 */
	public function post() {
		if ( IS_POST ) {
			$data                = json_decode( $_POST['data'], true );
			$model               = empty( $data['id'] ) ? new NavigateModel() : NavigateModel::find( $data['id'] );
			$data['css']['size'] = min( intval( $data['css']['size'] ), 100 );
			$model->save( $data );
			$url = u( 'site/navigate/lists', [
				'entry' => 'home',
				'm'     => 'article',
				'webid' => $data['webid']
			] );
			message( '保存导航数据成功', url('navigate/lists',['entry'=>'home','webid'=>$this->webid]), 'success' );
		}
		//站点列表
		$web = Db::table( 'web' )
		         ->field( 'web.id,web.title,template.tid,template.position' )
		         ->join( 'template', 'template.name', '=', 'web.template_name' )
		         ->where( 'siteid', SITEID )
		         ->get();
		if ( $this->id ) {
			$field        = Db::table( 'navigate' )->where( 'id', $this->id )->first();
			$field['css'] = empty( $field['css'] ) ? [ ] : json_decode( $field['css'], true );
		} else {
			/**
			 * 新增数据时初始化导航数据
			 * 只有通过官网添加导航链接才有效
			 */
			$field['siteid']   = SITEID;
			$field['position'] = 0;
			$field['icontype'] = 1;
			$field['status']   = 1;
			$field['orderby']  = 0;
			$field['entry']    = 'home';
			$field['css']      = [ 'icon' => 'fa fa-external-link', 'image' => '', 'color' => '#333333', 'size' => 35 ];
			$field['webid']    = $this->webid;
		}
		View::with( 'web', Arr::stringToInt( $web ) );
		View::with( 'field', Arr::stringToInt( $field ) );

		return view( $this->template . '/post.html' );
	}

	//删除菜单
	public function del() {
		NavigateModel::delete( $this->id );
		message( '菜单删除成功', 'back', 'success' );
	}
}