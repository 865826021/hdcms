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

class reg extends web {
    //注册页面
    public function register() {
        if ( IS_POST ) {
            Validate::make( [
                [ 'username', 'required', '请填写手机号或邮箱', 3 ],
                [ 'password', 'required', '密码不能为空', 3 ],
                [ 'password', 'confirm:password2', '两次密码不一致', 3 ],
            ] );
            if ( Validate::fail() ) {
                message( Validate::getError(), 'back', 'error' );
            };
            $data['mobile'] = $data['email'] = '';
            switch ( v( 'setting.register.item' ) ) {
                case 1:
                    //手机号注册
                    if ( ! preg_match( '/^\d{11}$/', $_POST['username'] ) ) {
                        message( '请输入手机号', 'back', 'error' );
                    }
                    $data['mobile'] = $_POST['username'];
                    break;
                case 2:
                    //手机号注册
                    if ( ! preg_match( '/\w+@\w+/', $_POST['username'] ) ) {
                        message( '请输入邮箱', 'back', 'error' );
                    }
                    $data['email'] = $_POST['username'];
                    break;
                case 3:
                    if ( ! preg_match( '/^\d{11}$/', $_POST['username'] ) && ! preg_match( '/\w+@\w+/', $_POST['username'] ) ) {
                        message( '请输入邮箱或手机号', 'back', 'error' );
                    } else if ( preg_match( '/^\d{11}$/', $_POST['username'] ) ) {
                        $data['mobile'] = $_POST['username'];
                    } else {
                        $data['email'] = $_POST['username'];
                    }
            }

            if ( Db::table( 'member' )->where( 'email', $_POST['username'] )->orWhere( 'mobile', $_POST['username'] )->get() ) {
                message( '用户名已经存在', 'back', 'error' );
            }
            $data['siteid'] = $this->siteid;
            $data['security'] = substr( md5( time() ), 0, 8 );
            $data['password'] = md5( $_POST['password'] . $data['security'] );
            //获取默认组
            $data['group_id'] = Db::table( 'member_group' )->where( 'siteid', $this->siteid )->where( 'isdefault', 1 )->pluck( 'id' );
            Db::table( 'member' )->insert( $data );
            message( '恭喜你,注册成功!系统将跳转到登录页面', u( 'login', [ 'i' => $this->siteid ] ), 'success' );
        }
        View::with( 'placeholder', v( 'setting.register.item' ) == 1 ? '手机号' : ( v( 'setting.register.item' ) == 2 ? '邮箱' : '手机号/邮箱' ) );
        View::make( $this->ucenter_template . '/register.html' );
    }

    //登录
    public function login() {

        if ( IS_POST ) {
            $user = Db::table( 'member' )->where( 'email', $_POST['username'] )->orWhere( 'mobile', $_POST['username'] )->first();
            if ( empty( $user ) ) {
                message( '帐号不存在', 'back', 'error' );
            }
            if ( md5( $_POST['password'] . $user['security'] ) != $user['password'] ) {
                message( '密码输入错误', 'back', 'error' );
            }
            Session::set( 'user', $user );
            //回调地址
            $backurl = q( 'get.backurl', u( 'entry/home', [ 'i' => $_GET['i'] ] ), 'htmlentities' );
            message( '登录成功', $backurl, 'success' );
        }
        View::make( $this->ucenter_template . '/login.html' );
    }

    //使用微信openid登录
    public function openidLogin() {
        Util::loginByOpenid();
    }

    //退出
    public function out() {
        Session::flush();
        message( '退出成功', u( 'login', [ 'i' => $this->siteid ] ), 'success' );
    }

    //找回密码
    public function retrieve() {

    }
}