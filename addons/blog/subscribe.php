<?php namespace addons\blog;
/**
 * 博客模块消息订阅器
 *
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\hdSubscribe;
class Subscribe extends hdSubscribe
{
	public function handle()
	{
		p($this->message());
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看HDCMS文档来编写你的代码
	}
}