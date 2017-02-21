<?php namespace addons\salary\service;
use module\HdService;
/**
 * 模块服务
 * 服务器就是为了实现模块间通信功能
 * @author 后盾团队
 * @url http://open.hdcms.com
 */
class Hd extends HdService{
	/**
	 * 调用方式
	 * service('article.hd.make')
	 * service('模块.方法')
	 */
	public function make() {
		echo '服务调用成功';
	}
}