<?php namespace module\ucenter\system;
use module\HdPay;
/**
 * 微信支付通知页面
 * Class Pay
 * @package module\ucenter\system
 */
class Pay extends HdPay {
	/**
	 * 微信同步通知
	 *
	 * @param bool $code true 支付成功
	 * @param array $order 定单信息
	 */
	public function sync( $code, $order ) {
		if ( $code ) {
			//模块业务处理
			Db::table( 'balance' )->where( 'tid', $order['tid'] )->update( [ 'status' => 1 ] );
			message( '支付成功,系统将跳转到会员中心', url( 'member/index', [ ], $order['module'] ), 'success' );
		} else {
			message( '支付成功,系统将跳转到会员中心', url( 'member/index', [ ], $order['module'] ), 'error' );
		}
	}

	/**
	 * 微信异步通知
	 *
	 * @param bool $code true 支付成功
	 * @param array $order 定单信息
	 */
	public function async( $code, $order ) {
		if ( $code ) {
			//模块业务处理
			Db::table( 'balance' )->where( 'tid', $order['tid'] )->update( [ 'status' => 1 ] );
		}
	}
}