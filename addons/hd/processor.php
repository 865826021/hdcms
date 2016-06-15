<?php namespace addons\hd;
/**
 * 后盾模块消息处理器
 *
 * @author 作者
 * @url http://open.hdcms.com
 */
use module\hdProcessor;
class Processor extends hdProcessor
{
	public function handle($rid)
	{
		$this->text('abc');

//		p($this->message);
		//这里定义此模块进行消息处理的, 消息到达以后的具体处理过程, 请查看HDCMS文档来编写你的代码
	}
}