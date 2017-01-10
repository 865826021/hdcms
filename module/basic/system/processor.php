<?php namespace module\basic;

/**
 * 测试模块模块消息处理器
 *
 * @author 后盾网
 * @url http://open.hdcms.com
 */
use module\hdProcessor;

class processor extends hdProcessor {
	//规则编号
	public function handle( $rid ) {
		$sql = "SELECT * FROM hd_reply_basic WHERE rid={$rid} ORDER BY rand()";
		if ( $res = Db::query( $sql ) ) {
			$this->text( $res[0]['content'] );

			return true;
		}
	}
}