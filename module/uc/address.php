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

/**
 * 地址管理
 * Class address
 * @package member\address
 * @author 向军
 */
class address extends web {
    //地址列表
    public function lists() {
        $data = Db::table( 'member_address' )
                  ->orderBy( 'isdefault', 'DESC' )
                  ->orderBy( 'id', 'asc' )
                  ->where( 'siteid', $this->siteid )
                  ->where( 'uid', $_SESSION['user']['uid'] )
                  ->get();
        View::with( 'data', $data );
        View::make( $this->ucenter_template . '/address_lists.html' );
    }

    public function post() {
        if ( IS_POST ) {
            $data['siteid']   = $this->siteid;
            $data['uid']      = $_SESSION['user']['uid'];
            $data['username'] = $_POST['username'];
            $data['mobile']   = $_POST['mobile'];
            $data['zipcode']  = $_POST['zipcode'];
            $data['province'] = $_POST['province'];
            $data['city']     = $_POST['city'];
            $data['district'] = $_POST['district'];
            $data['address']  = $_POST['address'];
            //不存在默认地址时将此地址设置为默认
            if ( ! Db::table( 'member_address' )->where( 'uid', $_SESSION['user']['uid'] )->where( 'siteid', $this->siteid )->get() ) {
                $data['isdefault'] = 1;
            }
            if ( $id = q( 'get.id', 0, 'intval' ) ) {
                $address = Db::table( 'member_address' )->where( 'id', $id )->where( 'siteid', $this->siteid )->first();
                if ( $address['uid'] == $_SESSION['user']['uid'] && $address['siteid'] == $this->siteid ) {
                    $data['id']        = $id;
                    $data['isdefault'] = $address['isdefault'];
                } else {
                    message( '请求错误', u( 'member/uc/home' ), 'error' );
                }
            }

            Db::table( 'member_address' )->replace( $data );
            message( '保存地址成功', u( 'lists', [ 'i' => $this->siteid ] ), 'success' );
        }
        if ( $id = q( 'get.id', 0, 'intval' ) ) {
            $data = Db::table( 'member_address' )
                      ->where( 'id', $id )
                      ->where( 'siteid', $this->siteid )
                      ->where( 'uid', $_SESSION['user']['uid'] )
                      ->first();
            View::with( 'field', $data );
        }
        View::make( $this->ucenter_template . '/address_post.html' );
    }

    //修改默认地址
    public function changeDefault() {
        $id = q( 'id', 0, 'intval' );
        $ad = Db::table( 'member_address' )->where( 'uid', $_SESSION['user']['uid'] )->where( 'siteid', $this->siteid )->where( 'id', $id )->first();
        if ( $ad ) {
            //删除原来的默认地址
            Db::table( 'member_address' )->where( 'uid', $_SESSION['user']['uid'] )->where( 'siteid', $this->siteid )->update( [ 'isdefault' => 0 ] );
            //将当前地址设置为默认
            Db::table( 'member_address' )->where( 'id', $id )->update( [ 'isdefault' => 1 ] );
        }
    }

    //删除地址
    public function remove() {
        $id = q( 'id', 0, 'intval' );
        $ad = Db::table( 'member_address' )->where( 'uid', $_SESSION['user']['uid'] )->where( 'siteid', $this->siteid )->where( 'id', $id )->first();
        if ( $ad ) {
            //删除地址
            Db::table( 'member_address' )->where( 'id', $id )->delete();
            //如果删除的是默认地址,重新设置地址
            if ( $ad['isdefault'] ) {
                Db::table( 'member_address' )
                  ->where( 'uid', $_SESSION['user']['uid'] )
                  ->where( 'siteid', $this->siteid )
                  ->orderBy( 'id', 'asc' )
                  ->limit( 1 )
                  ->update( [ 'isdefault' => 1 ] );
            }
        }
    }
}