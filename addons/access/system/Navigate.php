<?php namespace addons\access\system;

/**
 * 模块导航菜单处理
 *
 * @author fds
 * @url http://open.hdcms.com
 */
use module\HdNavigate;

class Navigate extends HdNavigate {

	/**
	 * 桌面入口 [桌面入口导航菜单]
	 * 在网站管理中将模块设置为默认执行模块然后配置好域名
	 * 当使用配置的域名访问时会执行这个方法
	 */
    public function zuomian() {
    }

	/**
	 * 会员中心1 [桌面会员中心菜单]
	 * 使用PC端访问时在会员中心显示的菜单
	 */
    public function member1() {
    }

	/**
	 * 会员中心2 [桌面会员中心菜单]
	 * 使用PC端访问时在会员中心显示的菜单
	 */
    public function member2() {
    }

	/**
	 * 会员中心1 [移动端会员中心菜单]
	 * 使用移动端设备如手机访问时
	 * 在会员中心显示的菜单
	 */
    public function mem1() {
    }

	/**
	 * 会员中心2 [移动端会员中心菜单]
	 * 使用移动端设备如手机访问时
	 * 在会员中心显示的菜单
	 */
    public function mem2() {
    }


}