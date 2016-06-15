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
		//扩展模块访问
		if ( isset( $_GET['a'] ) ) {
			$_GET['s'] = 'site/module/entry';
		}
		$this->siteInitialize();
	}

	//初始化站点
	protected function siteInitialize() {
		$siteModel = new Site();
		//缓存站点数据
		$siteid = q( 'get.siteid', Session::get( 'siteid' ), 'intval' );
		if ( empty( $siteid ) ) {
			return;
		}
		Session::set( 'siteid', $siteid );
		if ( $siteModel->find( $siteid ) ) {
			//加载站点缓存
			$siteModel->loadSite();
		} else {
			Session::del( 'siteid' );
			message( '你访问的站点不存在', 'back', 'error' );
		}
	}
}
