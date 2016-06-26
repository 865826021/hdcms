<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace uc\controller;

use web\web;

class account extends web {
	//帐户充值
	public function balance() {
		if ( IS_POST ) {
			//检测是否有相同金额的充值记录,如果有就使用它,避免重复金额定单产生
			$pay                = Db::table( 'balance' )->where( 'fee', $_POST['fee'] )->where( 'status', 0 )->pluck( 'tid' );
			$data['tid']        = $pay ? $pay : Cart::getOrderId();
			$data['goods_name'] = '会员充值';
			$data['fee']        = $_POST['fee'];
			$data['body']       = '会员余额充值';
			$data['back_url']   = __ROOT__ . '/index.php?s=uc/entry/home';//支付回调地址
			$data['attach']     = '';//附加数据
			//在会员充值表中记录定单
			if ( empty( $pay ) ) {
				$balance['siteid']     = v( 'site.siteid' );
				$balance['uid']        = Session::get( 'user.uid' );
				$balance['fee']        = $data['fee'];
				$balance['stauts']     = 0;
				$balance['createtime'] = time();
				$balance['tid']        = $data['tid'];
				Db::table( 'balance' )->insert( $balance );
			}
			Util::pay( $data );
		}
		View::make( $this->ucenter_template . '/balance.html' );
	}
}