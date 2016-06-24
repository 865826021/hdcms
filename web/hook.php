<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web;

use system\model\Site;

class hook {
	//系统自动执行此方法
	public function app_begin() {
		//异步时隐藏父模板
		IS_AJAX and c( 'view.blade', FALSE );
		$this->moduleInitialize();
		$this->siteInitialize();
	}

	//模块初始化
	protected function moduleInitialize() {
		//扩展模块访问
		if ( ! empty( $_GET['a'] ) ) {
			$_GET['s'] = 'site/module/entry';
			$name      = current( explode( '/', $_GET['a'] ) );
			v( 'module', Db::table( 'modules' )->where( 'name', $name )->first() );
		} else if ( $name = q( 'get.m' ) ) {
			v( 'module', Db::table( 'modules' )->where( 'name', $name )->first() );
		}
	}

	//站点初始化
	protected function siteInitialize() {
		$siteModel = new Site();
		//缓存站点数据
		$siteid = q( 'get.siteid', Session::get( 'siteid' ), 'intval' );
		if ( $siteid ) {
			Session::set( 'siteid', $siteid );
			if ( $siteModel->find( $siteid ) ) {
				define( 'SITEID', $siteid );
				//加载站点缓存
				$siteModel->loadSite();
			} else {
				Session::del( 'siteid' );
				message( '你访问的站点不存在', 'back', 'error' );
			}
		}
	}
}
