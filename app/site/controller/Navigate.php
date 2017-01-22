<?php namespace app\site\controller;

use system\model\Navigate as NavigateModel;
use system\model\Page;

/**
 * 模块导航菜单管理
 * Class Navigate
 * @package app\site\controller
 */
class Navigate {
	public function __construct() {
		auth();
		$this->webid = Request::get( 'webid' );
		/**
		 * 没有站点编号时设置站点编号
		 * 在前台添加菜单时使用
		 * 因为添加菜单只能给微站首页添加必须有这个字段
		 */
		if ( empty( $this->webid ) || ! \Web::has( $this->webid ) ) {
			$this->webid = Db::table( 'web' )->where( 'siteid', SITEID )->pluck( 'id' );
		}
	}

	//菜单列表管理
	public function lists() {
		if ( IS_POST ) {
			$data = json_decode( Request::post( 'data' ), true );
			foreach ( $data as $k => $nav ) {
				$nav['webid'] = Request::get( 'webid', 0 );
				$model        = empty( $nav['id'] ) ? new NavigateModel() : NavigateModel::find( $nav['id'] );
				$model->save( $nav );
			}
			message( '保存导航数据成功', 'refresh', 'success' );
		}
		/**
		 * 当为首页导航菜单时需要获取微站编号
		 * 如果没有指定微信编号时获取默认站点
		 * 如果没有默认站点不允许设置首页导航菜单
		 * 没有站点编号时设置站点编号并刷新页面
		 */
		if ( Request::get( 'entry' ) == 'home' ) {
			$webid = Request::get( 'webid' );
			if ( empty( $webid ) ) {
				if ( $web = \Web::getDefaultWeb() ) {
					go( __URL__ . '&webid=' . $web['id'] );
				}
			}
			if ( empty( $webid ) ) {
				message( '请先在文章系统添加站点后进行操作', 'back', 'error' );
			}
			//当前站点模板数据
			$template = \Template::getTemplateData( $this->webid );
			View::with( 'template', $template );
			View::with( 'template_position_data', \Template::getPositionData( $template['tid'] ) );
			View::with( 'webid', $webid );
		}
		/**
		 * 获取导航菜单
		 * entry是导航类型:home微站首页导航/profile手机会员中心导航/member桌面会员中心导航
		 */
		if ( Request::get( 'entry' ) == 'home' ) {
			//站点首页导航时需要根据站点编号读取导航菜单
			$nav = Db::table( 'navigate' )
			         ->where( 'webid', $this->webid )
			         ->where( 'entry', Request::get( 'entry' ) )
			         ->where( 'module', v( 'module.name' ) )
			         ->get();
		} else {
			//其他类型导航不需要站点编号条件
			$nav = Db::table( 'navigate' )
			         ->where( 'entry', Request::get( 'entry' ) )
			         ->where( 'module', v( 'module.name' ) )
			         ->get();
		}
		/**
		 * 扩展模块动作时将没有添加到数据库中的菜单添加到列表中
		 * 根据模块菜单的URL进行比较
		 */
		if ( v( 'module.name' ) != 'article' ) {
			$moduleMenu = Db::table( 'modules_bindings' )->where( 'module', v( 'module.name' ) )->where( 'entry', Request::get( 'entry' ) )->get();
			foreach ( $moduleMenu as $k => $v ) {
				$moduleMenu[ $k ]['url'] = "?m={$v['module']}&action=system/navigate/{$v['do']}&siteid=" . SITEID;
				foreach ( $nav as $n ) {
					//如果模块的菜单已经添加到数据库中的将这个菜单从列表中移除
					if ( $n['url'] == $moduleMenu[ $k ]['url'] ) {
						unset( $moduleMenu[ $k ] );
					}
				}
			}
			//没有添加到数据库中的菜单组合出可用于数据库的数据结构
			foreach ( $moduleMenu as $v ) {
				$nav[] = [
					'webid'    => 0,
					'module'   => v( 'module.name' ),
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
					'status'   => 1,
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
		View::with( 'nav', Arr::stringToInt( $nav ) );

		return view();
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
			$data['module']      = Request::get( 'm' );
			$model               = empty( $data['id'] ) ? new NavigateModel() : NavigateModel::find( $data['id'] );
			$data['css']['size'] = min( intval( $data['css']['size'] ), 100 );
			$model->save( $data );
			$url = u( 'lists', [
				'entry' => 'home',
				'm'     => 'article',
				'webid' => $data['webid']
			] );
			message( '保存导航数据成功', $url, 'success' );
		}
		//站点列表
		$web = Db::table( 'web' )
		         ->field( 'web.id,web.title,template.tid,template.position' )
		         ->join( 'template', 'template.name', '=', 'web.template_name' )
		         ->where( 'siteid', SITEID )
		         ->get();
		$id  = Request::get( 'id' );
		if ( $id ) {
			$field        = Db::table( 'navigate' )->where( 'id', $id )->first();
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
			$field['webid']    = 0;
		}
		View::with( 'web', Arr::stringToInt( $web ) );
		View::with( 'field', Arr::stringToInt( $field ) );

		return view();
	}

	//删除菜单
	public function del() {
		NavigateModel::delete( Request::get( 'id' ) );
		message( '菜单删除成功', 'back', 'success' );
	}

	//移动端页面快捷导航
	public function quickmenu() {
		if ( IS_POST ) {
			$data  = json_decode( $_POST['data'], true );
			$model = empty( $data['id'] ) ? new Page() : Page::find( $data['id'] );
			$model->save( $data );
			message( '保存快捷菜单成功', 'refresh', 'success' );
		}
		$field = Db::table( 'page' )->where( 'siteid', SITEID )->where( 'type', 'quickmenu' )->first();
		if ( $field ) {
			$field           = Arr::stringToInt( $field );
			$field['params'] = json_decode( $field['params'], true );
		}

		return view()->with( 'field', $field );
	}
}