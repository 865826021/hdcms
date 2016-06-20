<?php namespace module\basic;

use module\hdProcessor;

/**
 * 封面消息处理
 * Class processor
 * @package module\basic
 */
class processor extends hdProcessor {
	//规则编号
	public function handle( $rid ) {
		$res = Db::table( 'reply_cover' )->where( 'rid', '=', $rid )->where( 'siteid', v( 'site.siteid' ) )->first();
		if ( $res ) {
			$data[] = [
				'title'       => $res['title'],
				'discription' => $res['description'],
				'picurl'      => __ROOT__ . '/' . $res['thumb'],
				'url'         => __ROOT__ . '/' . $res['url'],
			];
			$this->news( $data );

			return TRUE;
		}
	}
}
