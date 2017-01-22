<?php namespace app\site\controller;
/**
 * 网站支付处理
 * Class Pay
 * @package web\site
 */
class Pay {
	//微信支付
	public function wechat() {
		$pay                  = Db::table( 'pay' )->where( 'tid', Session::get( 'pay.tid' ) )->first();
		$data['total_fee']    = $pay['fee'] * 100;//支付金额单位分
		$data['body']         = $pay['body'];//商品描述
		$data['attach']       = $pay['attach'];//附加数据
		$data['out_trade_no'] = $pay['tid'];//会员定单号
		c( 'weixin.notify_url', __ROOT__ . '/index.php/wxnotifyurl/' . SITEID );
		c( 'weixin.back_url', web_url( 'pay/notify', Session::get( 'pay' ), Session::get( 'pay.module' ) ) );
		Weixin::instance( 'pay' )->jsapi( $data );
	}

	//微信支付成功回调函数
	public function weixinNotify() {
		$res            = Weixin::instance( 'pay' )->getNotifyMessage();
		$pay            = Db::table( 'pay' )->where( 'tid', $res['out_trade_no'] )->first();
		$data['status'] = 1;
		$data['type']   = 'wechat';
		Db::table( 'pay' )->where( 'tid', $res['out_trade_no'] )->update( $data );
		//记录支付记录
		$log['siteid'] = SITEID;
		$log['uid']    = $pay['uid'];
		Db::table( 'credits_record' )->insert( $log );
		$module = Db::table( 'modules' )->where( 'name', $pay['module'] )->first();
		//通知模块
		if ( $module['is_system'] == 1 ) {
			//系统模块
			$class = '\module\\' . $pay['module'] . '\pay';
		} else {
			$class = '\addons\\' . $pay['module'] . '\pay';
		}
		if ( class_exists( $class ) ) {
			$obj = new $class( $pay['module'] );
			$obj->doWebAsync( $res );
		}
	}
}