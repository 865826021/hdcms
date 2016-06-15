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

class pay extends web {
    //系统模块
    protected $systemModules = [ 'balance' ];

    //微信支付
    public function wechat() {
        $pay                  = Db::table( 'pay' )->where( 'tid', Session::get( 'pay.tid' ) )->first();
        $data['total_fee']    = $pay['fee'] * 1;//支付金额单位分
        $data['body']         = $pay['body'];//商品描述
        $data['attach']       = $pay['attach'];//附加数据
        $data['out_trade_no'] = $pay['tid'];//会员定单号

        c( 'weixin.appid', v( 'wechat.appid' ) );
        c( 'weixin.mch_id', v( 'setting.pay.weichat.mch_id' ) );
        c( 'weixin.key', v( 'setting.pay.weichat.key' ) );
        c( 'weixin.key', v( 'setting.pay.weichat.key' ) );
        c( 'weixin.notify_url', __ROOT__ . '/index.php/wxnotifyurl/' . v( 'site.siteid' ) );
        c( 'weixin.back_url', Session::get( 'pay.back_url' ) );
        Weixin::instance( 'pay' )->jsapi( $data );
    }

    //微信支付成功回调函数
    public function notify() {
        $res = Weixin::instance( 'pay' )->getNotifyMessage();
        $pay = Db::table( 'pay' )->where( 'tid', $res['out_trade_no'] )->first();
        if ( $pay['status'] == 0 ) {
            $data['status'] = 1;
            $data['type']   = 'wechat';
            Db::table( 'pay' )->where( 'tid', $res['out_trade_no'] )->update( $data );
            //记录支付记录
            $log['siteid'] = v( 'site.siteid' );
            $log['uid']    = $pay['uid'];
            Db::table( 'credits_record' )->insert( $log );
            //通知模块
            if ( in_array( $pay['module'], $this->systemModules ) ) {
                //系统模块
                $class = '\module\\' . $pay['module'] . '\pay';
            } else {
                $class = '\addons\\' . $pay['module'] . '\pay';
            }
            if ( class_exists( $class ) ) {
                $obj = new $class( $pay['module'] );
                $obj->handle( $res['out_trade_no'] );
            }
        }
    }
}