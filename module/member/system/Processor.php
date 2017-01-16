<?php namespace module\member\system;

/**
 * 测试模块模块消息处理器
 *
 * @author 后盾网
 * @url http://open.hdcms.com
 */
use module\hdProcessor;
use system\model\Member;

class Processor extends hdProcessor {
	//规则编号
	public function handle( $rid ) {
		//关注时添加粉丝信息
		if ( $this->isSubscribeEvent() ) {
			$model = new Member();
			if ( $model->where( 'openid', $this->message->FromUserName )->first() ) {
				$model['openid'] = $this->message->FromUserName;
				$model->save();
			}
		}
	}
}