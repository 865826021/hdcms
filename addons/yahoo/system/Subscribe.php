<?php namespace addons\yahoo\system;

/**
 * 测试模块消息订阅器
 *
 * @author author
 * @url http://open.hdcms.com
 */
use module\HdSubscribe;
class Subscribe extends HdSubscribe{
	
	/**
	 * 微信消息订阅处理
	 * 微信有新消息后会发到这个方法
	 * 本方法只做微信消息分析
	 * 不要在这里直接回复微信消息,否则会影响整个系统的稳定性
	 * 微信消息类型很多, 系统已经内置了"后盾网微信接口SDK"
	 * 要更全面的使用本功能请查看 SDK文档
	 * @author author
	 * @url http://www.hdcms.com
	 */
	public function handle(){
		//此处理书写订阅处理代码
	}
}