<?php namespace addons\store\controller;

/**
 * 模块导航菜单处理
 *
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\HdController;

class Navigate extends HdController {

	/**
	 * 桌面入口导航 [桌面入口导航菜单]
	 * 在网站管理中将模块设置为默认执行模块然后配置好域名
	 * 当使用配置的域名访问时会执行这个方法
	 */
    public function home() {
    	echo '33';
    }


}