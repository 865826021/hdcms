<?php namespace system\service\pay;

use system\model\Pay as PayModel;

/**
 * 支付管理
 * Class Pay
 * @package system\service\cloud
 */
class Pay {
	/**
	 * 微信支付
	 *
	 * @param $param
	 */
	public function weChat( $param ) {
		if ( ! v( 'module.name' )
		     || ! v( 'member.info.uid' )
		     || empty( $param['goods_name'] )
		     || empty( $param['fee'] )
		     || empty( $param['body'] )
		     || empty( $param['tid'] )
		) {
			message( '支付参数错误,请重新提交', 'back', 'error' );
		}
		if ( $pay = Db::table( 'pay' )->where( 'tid', $param['tid'] )->first() ) {
			if ( $pay['status'] == 1 ) {
				message( '定单已经支付完成', url( 'member.index', [ ], 'ucenter' ), 'success' );
			}
		}

		$data['siteid']     = SITEID;
		$data['uid']        = v( 'member.info.uid' );
		$data['tid']        = $param['tid'];
		$data['fee']        = $param['fee'];
		$data['type']       = 'wechat';
		$data['createtime'] = time();
		$data['goods_name'] = $param['goods_name'];
		$data['attach']     = ( isset( $param['attach'] ) ? $param['attach'] : '' );
		$data['body']       = $param['body'];
		$data['module']     = v( 'module.name' );
		$data['status']     = 0;
		$data['is_usecard'] = isset( $param['is_usecard'] ) ? $param['is_usecard'] : 0;
		$data['card_type']  = isset( $param['card_type'] ) ? $param['card_type'] : '';
		$data['card_id']    = isset( $param['is_usecard'] ) ? $param['card_id'] : 0;
		$data['card_fee']   = isset( $param['card_fee'] ) ? $param['card_fee'] : 0;
		$model              = new PayModel();
		$model->save( $data );
		View::with( 'data', $data );
		echo view( 'system/service/pay/template/pay.html' );
		die;
	}
}