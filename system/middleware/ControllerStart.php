<?php namespace system\middleware;

use houdunwang\request\Request;
use system\model\Modules;
use system\model\Router;

/**
 * CMS系统初始中间件
 * Class ControllerStart
 * @package system\middleware
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class ControllerStart {
	public function run() {
		//异步时隐藏父模板
		IS_AJAX and c( 'view.blade', false );
		//初始站点数据
		\Site::siteInitialize();
		//初始模块数据
		\Module::moduleInitialize();
		//载入后台用户信息到全局变量
		\User::initUserInfo();
		//载入前台用户信息到全局变量
		\Member::initMemberInfo();
	}
}