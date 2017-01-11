<?php namespace system\middleware;

use houdunwang\request\Request;
use system\model\Modules;

/**
 * CMS系统初始中间件
 * Class Initialize
 * @package system\middleware
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Initialize {

	public function run() {
		//异步时隐藏父模板
		IS_AJAX and c( 'view.blade', false );
		//后台用户
		\User::initUserInfo();
		//前台用户
		\Member::initUserInfo();
		//加载系统配置项,对是系统配置不是站点配置
		$this->loadConfig();
		//初始站点数据
		$this->siteInitialize();
		//初始模块数据
		$this->moduleInitialize();
	}

	//加载系统配置项
	protected function loadConfig() {
		$config = Db::table( 'config' )->field( 'site,register' )->first();
		/**
		 * 不存在配置项时
		 * 一般在系统初次安装时会发生这个情况
		 */
		$config['site']     = json_decode( $config['site'], true );
		$config['register'] = json_decode( $config['register'], true );
		v( 'config', $config );
	}

	//模块初始化
	protected function moduleInitialize() {
		/**
		 * 初始化模块数据
		 * 加载模块数据到全局变量窗口中
		 */
		if ( $name = Request::get( 'm' ) ) {
			v( 'module', Modules::where( 'name', $name )->first() );
		}

		/**
		 * 扩展模块单独使用变量访问
		 * 而不是使用框架中的s变量
		 * 所以当存在a变量时访问到扩展模块处理
		 */
		if ( Request::get( 'm' ) && Request::get( 'action' ) ) {
			Request::set( 'get.s', 'site/entry/action' );
		}
	}

	//站点信息初始化
	protected function siteInitialize() {
		/**
		 * 站点编号
		 * 如果GET中存在siteid使用,否则使用SESSION会话中的siteid
		 */
		$siteId = q( 'get.siteid', Session::get( 'siteid' ), 'intval' );
		if ( $siteId ) {
			if ( Db::table( 'site' )->find( $siteId ) ) {
				define( 'SITEID', $siteId );
				Session::set( 'siteid', $siteId );
				\Site::loadSite( $siteId );
			} else {
				message( '你访问的站点不存在', 'back', 'error' );
			}
		}
	}
}