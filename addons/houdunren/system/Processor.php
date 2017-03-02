<?php namespace addons\houdunren\system;

/**
 * 测试模块消息处理器
 *
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\HdProcessor;
class Processor extends HdProcessor{
	/**
	 * 微信消息处理
	 * $rid 为包含消息内容处理规则的编号
	 * 根据$rid 从当前模块的数据表中获取回复内容,然后进行回复处理
	 * 如果没有回复系统将回复默认消息内容
	 * 微信消息类型很多, 系统已经内置了"后盾网微信接口SDK"
	 * 要更全面的使用本功能请查看 SDK文档
	 * @param int $rid
	 */
	public function handle($rid){
		//此处书写微信回复代码
	}
}