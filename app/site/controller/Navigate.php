<?php namespace app\site\controller;

use system\model\Navigate as NavigateModel;
use system\model\Page;

/**
 * 模块导航菜单管理
 * Class Navigate
 * @package app\site\controller
 */
class Navigate {
	//验证权限
	protected function auth() {
		//对模块的会员中心菜单进行权限验证
		$entry = Request::get( 'entry' );
		if ( in_array( $entry, [ 'member', 'profile' ] ) ) {
			auth( 'system_' . $entry );
		} else {
			auth( 'article_navigate_lists' );
		}
	}

	//菜单列表管理
	public function lists() {
		$this->auth();
		if ( IS_POST ) {
			$data = json_decode( Request::post( 'data' ), true );
			foreach ( $data as $k => $nav ) {
				$model = empty( $nav['id'] ) ? new NavigateModel() : NavigateModel::find( $nav['id'] );
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
			//当前站点模板数据
			$template = \Template::getTemplateData();
			if ( empty( $template ) ) {
				message( '请先在站点设置中设置站点模板', '', 'error' );
			}
			View::with( 'template', $template );
			View::with( 'template_position_data', \Template::getPositionData( $template['tid'] ) );
		}
		/**
		 * 获取导航菜单
		 * entry是导航类型:home微站首页导航/profile手机会员中心导航/member桌面会员中心导航
		 */
		$nav = Db::table( 'navigate' )
		         ->where( 'siteid', SITEID )
		         ->where( 'entry', Request::get( 'entry' ) )
		         ->where( 'module', v( 'module.name' ) )
		         ->get();
		/**
		 * 扩展模块动作时将没有添加到数据库中的菜单添加到列表中
		 * 根据模块菜单的URL进行比较
		 */
		if ( v( 'module.name' ) != 'article' ) {
			$moduleMenu = Db::table( 'modules_bindings' )->where( 'module', v( 'module.name' ) )
			                ->where( 'entry', Request::get( 'entry' ) )->get();
			foreach ( $moduleMenu as $k => $v ) {
				$params                  = empty( $v['params'] ) ? '' : '&' . $v['params'];
				$moduleMenu[ $k ]['url'] = "?m={$v['module']}&action=system/navigate/{$v['do']}{$params}&siteid=" . SITEID;
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
		//对模块的会员中心菜单进行权限验证
		$this->auth();
		if ( IS_POST ) {
			$data                = json_decode( $_POST['data'], true );
			$data['module']      = Request::get( 'm' );
			$model               = empty( $data['id'] ) ? new NavigateModel() : NavigateModel::find( $data['id'] );
			$data['css']['size'] = min( intval( $data['css']['size'] ), 100 );
			$model->save( $data );
			$url = site_url( 'lists', [ 'entry' => Request::get( 'entry' ) ] );
			message( '保存导航数据成功', $url, 'success' );
		}
		$id = Request::get( 'id' );
		if ( $id ) {
			$field        = Db::table( 'navigate' )->where( 'id', $id )->first();
			$field['css'] = empty( $field['css'] ) ? [] : json_decode( $field['css'], true );
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
		}
		/**
		 * 站点导航菜单时
		 * 显示站点模板的菜单位置信息
		 */
		if ( Request::get( 'entry' ) == 'home' ) {
			//当前站点模板数据
			$template = \Template::getTemplateData();
			if ( empty( $template ) ) {
				message( '请先在站点设置中设置站点模板', '', 'error' );
			}
			View::with( 'template', $template );
			View::with( 'template_position_data', \Template::getPositionData( $template['tid'] ) );
		}
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
		auth();
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