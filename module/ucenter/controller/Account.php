<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\ucenter\controller;

/**
 * 会员积分余额管理
 * Class Credit
 * @package module\ucenter\controller
 */
class Account extends Auth {
	//帐户充值
	public function balance() {
		if ( IS_POST ) {
			//定单号
			$data['tid'] = \Cart::getOrderId();
			//商品名称
			$data['goods_name'] = '会员充值';
			//支付价格
			$data['fee'] = Request::post( 'fee' );
			//商品详情
			$data['body']       = '会员余额充值';
			$data['status']     = 0;
			$data['createtime'] = time();
			$data['siteid']     = SITEID;
			//附加数据
			$data['attach'] = '';
			Db::table( 'balance' )->insert( $data );
			\Pay::weChat( $data );
		}

		return View::make( $this->template . '/balance.html' );
	}
}