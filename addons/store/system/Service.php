<?php namespace addons\store\system;
use module\HdService;

/**
 * 模块服务
 * 服务器就是为了实现模块间通信功能
 * @author 向军
 * @url http://open.hdcms.com
 */
class Service extends HdService{
	/**
	 * 调用方式
	 * service('article.make')
	 * service('模块.方法')
	 */
	public function make() {
		echo '服务调用成功';
	}
}