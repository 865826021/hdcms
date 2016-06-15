<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace api;
//支付服务
class pay {
    //支付
    public function process( $param ) {
        if ( empty( $_GET['m'] ) || ! Session::get( 'user.uid' ) || empty( $param['goods_name'] )
             || empty( $param['fee'] )
             || empty( $param['back_url'] )
             || empty( $param['body'] )
             || empty( $param['tid'] )
        ) {
            message( '支付参数错误,请重新提交', 'back', 'error' );
        }

        if ( $pay = Db::table( 'pay' )->where( 'tid', $param['tid'] )->first() ) {
            if ( $pay['status'] == 1 ) {
                message( '定单已经支付完成', $param['back_url'], 'success' );
            }
        }
        $data['siteid']     = v( 'site.siteid' );
        $data['uid']        = Session::get( 'member.uid' );
        $data['tid']        = $param['tid'];
        $data['fee']        = $param['fee'];
        $data['goods_name'] = $param['goods_name'];
        $data['attach']     = isset( $param['attach'] ) ? $param['attach'] : '';//附加数据
        $data['module']     = $_GET['m'];
        $data['body']       = $param['body'];
        $data['attach']     = $param['attach'];
        $data['status']     = 0;
        $data['is_usecard'] = isset( $param['is_usecard'] ) ? $param['is_usecard'] : 0;
        $data['card_type']  = isset( $param['card_type'] ) ? $param['card_type'] : '';
        $data['card_id']    = isset( $param['is_usecard'] ) ? $param['card_id'] : 0;
        $data['card_fee']   = isset( $param['card_fee'] ) ? $param['card_fee'] : 0;
        if ( empty( $pay ) ) {
            Db::table( 'pay' )->insertGetId( $data );
        }
        View::with( 'data', $data );
        Session::set( 'pay', [ 'tid' => $data['tid'], 'back_url' => $param['back_url'] . '&i=' . v( "site.siteid" ) ] );
        View::make( 'theme/default/mobile/ucenter/pay.html' );
    }
}