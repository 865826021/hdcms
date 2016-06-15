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
 * 前台会员中心
 * Class entry
 * @package uc\controller
 */
class entry extends web {
    //显示会员中心页面
    public function home() {
        //没有登录时跳转到登录页面
        Util::isLogin();
        //更新用户session
        Util::updateUserSessionData();
        $uc = Db::table( 'web_page' )->where( 'siteid', $this->siteid )->where( 'type', 3 )->first();
        //获取菜单
        $menus = Db::table( 'web_nav' )->where( 'siteid', $this->siteid )->where( 'entry', 'profile' )->get();
        //会员信息
        $group = Util::getGroupName( Session::get( 'user.uid' ) );
        View::with( [ 'uc' => $uc, 'menus' => $menus, 'user' => Session::get( 'user' ), 'group' => $group ] );
        View::make( $this->ucenter_template . '/home.html' );
    }

    //修改手机号
    public function changeMobile() {
        if ( IS_POST ) {
            Validate::make( [
                    [ 'mobile', 'required|phone', '原手机号输入错误', 2 ],
                    [ 'new_mobile', 'required|phone', '新手机号输入错误', 2 ],
                    [ 'password', 'required', '密码不能为空', 2 ],
                ] );
            $user = Db::table( 'member' )->where( 'uid', $_SESSION['user']['uid'] )->first();
            if ( $user['password'] != md5( $_POST['password'] . $user['security'] ) ) {
                message( '密码输入错误', 'back', 'error' );
            }
            $data['mobile'] = $_POST['new_mobile'];
            Db::table( 'member' )->where( 'uid', $_SESSION['user']['uid'] )->update( $data );
            //更新用户session
            Util::updateUserSessionData();
            message( '手机号更新成功', u( 'home', [ 'i' => $this->siteid ] ), 'success' );
        }
        View::make( $this->ucenter_template . '/change_mobile.html' );
    }
}