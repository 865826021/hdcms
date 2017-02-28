<?php namespace system\middleware;

use houdunwang\request\Request;
use module\article\model\WebContent;
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
		$this->parseDomain();
		//初始站点数据
		\Site::siteInitialize();
		//初始模块数据
		\Module::moduleInitialize();
		//载入后台用户信息到全局变量
		\User::initUserInfo();
		//载入前台用户信息到全局变量
		\Member::initMemberInfo();
		//同步通知地址
		c( 'wechat.back_url', u( 'site.pay.weChatNotify' ) );
	}

	/**
	 * 地址中不存在动作标识
	 * s m action 时检测域名是否已经绑定到模块
	 * 如果存在绑定的模块时设置当请求的的模块
	 */
	protected function parseDomain() {
		$domain       = trim( $_SERVER['HTTP_HOST'] . dirname( $_SERVER['SCRIPT_NAME'] ), '/\\' );
		$moduleDomain = Db::table( 'module_domain' )->where( 'domain', $domain )->first();
		if ( $moduleDomain ) {
			if ( ! Request::get( 'siteid' ) ) {
				Request::set( 'get.siteid', $moduleDomain['siteid'] );
			}
			if ( ! Request::get( 'm' ) && ! Request::get( 's' ) && ! Request::get( 'action' ) ) {
				Request::set( 'get.m', $moduleDomain['module'] );
			}
		}
	}
}