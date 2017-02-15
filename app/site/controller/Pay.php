<?php namespace app\site\controller;
/**
 * 网站支付处理
 * Class Pay
 * @package web\site
 */
class Pay {
	//微信支付
	public function weChat() {
		$pay = Db::table( 'pay' )->where( 'tid', Request::get( 'tid' ) )->first();
		if ( empty( $pay ) ) {
			message( '定单不存在', '', 'error' );
		}
		if ( $pay['status'] == 1 ) {
			message( '定单已经支付', '', 'warning' );
		}
		$data['total_fee']    = $pay['fee'] * 1;//支付金额单位分
		$data['body']         = $pay['body'];//商品描述
		$data['attach']       = $pay['attach'];//附加数据
		$data['out_trade_no'] = $pay['tid'];//会员定单号
		//异步通知地址
		c( 'wechat.notify_url', __ROOT__ . '/index.php/wxnotifyurl/' . SITEID );

		\WeChat::instance( 'pay' )->jsapi( $data );
	}

	//微信支付异步回调函数
	public function weChatAsyncNotify() {
		//更新支付表状态
		$res  = Weixin::instance( 'pay' )->getNotifyMessage();
		$code = $res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS';
		if ( $code ) {
			//支付成功
			$this->updateOrderStatus( $res['out_trade_no'], 'wechat' );
		}
		//记录支付记录
//		$log['siteid'] = SITEID;
//		$log['uid']    = $pay['uid'];
//		Db::table( 'credits_record' )->insert( $log );

		Credit::change();

		return $this->informModule( $res['out_trade_no'], 'async', $code );
	}

	//微信支付同步通知
	public function weChatNotify() {
		$code = Request::get( 'code' ) == 'SUCCESS';
		if ( $code ) {
			$this->updateOrderStatus( Request::get( 'out_trade_no' ), 'wechat' );
		}

		return $this->informModule( Request::get( 'out_trade_no' ), 'sync', $code );
	}

	/**
	 * 模块通知
	 *
	 * @param $out_trade_no
	 * @param $action sync:同步通知 async:异步
	 * @param $code
	 *
	 * @return mixed
	 */
	public function informModule( $out_trade_no, $action, $code ) {
		$order = Db::table( 'pay' )->where( 'tid', $out_trade_no )->first();
		//向模块发送通知
		$module = Db::table( 'modules' )->where( 'name', $order['module'] )->first();
		//通知模块
		if ( $module['is_system'] == 1 ) {
			//系统模块
			$class = '\module\\' . $order['module'] . '\system\Pay';
		} else {
			$class = '\addons\\' . $order['module'] . '\system\Pay';
		}
		if ( class_exists( $class ) ) {
			return call_user_func_array( [ new $class, $action ], [ 'code' => $code, 'order' => $order ] );
		}
	}

	/**
	 * 更新定单表定单状态
	 *
	 * @param $out_trade_no 定单号
	 * @param $type 支付类型 wechat alipay
	 *
	 * @return mixed
	 */
	protected function updateOrderStatus( $out_trade_no, $type ) {
		$model = \system\model\Pay::where( 'tid', $out_trade_no )->first();
		if ( $model ) {
			$model['status'] = 1;
			$model->save();
			$data = [
				'uid'        => v( 'member.info.uid' ),
				'credittype' => 'credit2',
				'num'        => $model['fee'],
				'remark'     => $model['goods_name']
			];
			\Credit::change( $data );
		}
	}
}