<?php
namespace system\middleware;

/**
 * CMS系统初始中间件
 * Class Initialize
 * @package system\middleware
 */
class Initialize {
	//执行中间件
	public function run() {return;
		//异步时隐藏父模板
		IS_AJAX and c( 'view.blade', false );
		//初始用户信息
		$this->initUserInfo();
		//加载系统配置项,对是系统配置不是站点配置
		$this->loadConfig();
		//初始站点数据
		$this->siteInitialize();
		//初始模块数据
		$this->moduleInitialize();
	}

	//初始用户信息
	protected function initUserInfo() {
		//后台用户
		\User::initUserInfo();
		//前台用户
		\Member::initUserInfo();
	}

	//加载系统配置项
	protected function loadConfig() {
		$config             = Db::table( 'config' )->field( 'site,register' )->find( 1 );
		$config['site']     = json_decode( $config['site'], true );
		$config['register'] = json_decode( $config['register'], true );
		v( 'config', $config );
	}

	//模块初始化
	protected function moduleInitialize() {
		if ( $name = q( 'get.m' ) ) {
			v( 'module', Db::table( 'modules' )->where( 'name', $name )->first() );
		}
		//扩展模块访问
		if ( ! empty( $_GET['a'] ) ) {
			Request::get( 's', 'site/module/entry' );
		}
	}

	//站点信息初始化
	protected function siteInitialize() {
		//缓存站点数据
		$siteId = q( 'get.siteid', Session::get( 'siteid' ), 'intval' );
		if ( $siteId ) {
			if ( Db::table( 'site' )->find( $siteId ) ) {
				define( 'SITEID', $siteId );
				Session::set( 'siteid', $siteId );
				service( 'site' )->loadSite( $siteId );
			} else {
				message( '你访问的站点不存在', 'back', 'error' );
			}
		}
	}
}