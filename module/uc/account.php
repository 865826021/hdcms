<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\uc;

use module\hdSite;

/**
 * 帐户充值
 * Class account
 * @package module\uc
 * @author 向军
 */
class account extends hdSite {
	//帐户充值
	public function doWebBalance() {
		if ( IS_POST ) {
			//检测是否有相同金额的充值记录,如果有就使用它,避免重复金额定单产生
			$pay                = Db::table( 'balance' )
			                        ->where( 'fee', $_POST['fee'] )
			                        ->where( 'siteid', SITEID )
			                        ->where( 'status', 0 )
			                        ->where( 'uid', Session::get( 'member.uid' ) )
			                        ->pluck( 'tid' );
			$data['tid']        = $pay ? $pay : Cart::getOrderId();
			$data['goods_name'] = '会员充值';
			$data['fee']        = $_POST['fee'];
			$data['body']       = '会员余额充值';
			$data['attach']     = '';//附加数据
			//在会员充值表中记录定单
			if ( empty( $pay ) ) {
				$balance['siteid']     = SITEID;
				$balance['uid']        = Session::get( 'member.uid' );
				$balance['fee']        = $data['fee'];
				$balance['stauts']     = 0;
				$balance['createtime'] = time();
				$balance['tid']        = $data['tid'];
				Db::table( 'balance' )->insert( $balance );
			}
			Util::instance( 'pay' )->weixin( $data );
		}
		View::make( $this->ucenter_template . '/balance.html' );
	}


}