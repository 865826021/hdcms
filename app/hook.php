<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace app;

use system\model\Site;

class hook {
	//系统自动执行此方法
	public function app_begin() {
		echo 33;
		//异步时隐藏父模板
		IS_AJAX and c( 'view.blade', FALSE );
		//前台访问
		Session::set( 'is_member', isset( $_GET['t'] ) && $_GET['t'] == 'web' );
		//加载系统配置项,对是系统配置不是站点配置
		$this->loadConfig();
		//域名检测
		$this->checkDomain();
		$this->moduleInitialize();
		$this->siteInitialize();
	}


}
