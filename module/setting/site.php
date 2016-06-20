<?php

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\setting;

use module\hdSite;
use system\model\SiteSetting;

class site extends hdSite {
	protected $db;
	//主键
	protected $id;

	public function __construct() {
		parent::__construct();
		$this->db = new SiteSetting();
		$this->id = $this->db->where( 'siteid', v( 'site.siteid' ) )->pluck( 'id' );
	}

	//积分设置
	public function doSiteCredit() {
		if ( IS_POST ) {
			//积分/余额必须开启
			$_POST['creditnames']['credit1']['status'] = 1;
			$_POST['creditnames']['credit2']['status'] = 1;
			foreach ( $_POST['creditnames'] as $credit => $d ) {
				$_POST['creditnames'][ $credit ]['status'] = isset( $d['status'] ) ? intval( $d['status'] ) : 0;
			}
			$_POST['id'] = $this->id;
			$this->db->save();
			m( 'Site' )->updateSiteCache();
			message( '积分设置成功', '', 'success' );
		}
		View::with( 'creditnames', v( 'setting.creditnames' ) );
		View::make( $this->template . '/credit.html' );
	}

	//积分策略
	public function doSiteTactics() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			$this->db->save();
			m( 'Site' )->updateSiteCache();
			message( '积分策略更新成功', '', 'success' );
		}
		View::make( $this->template . '/tactics.html' );
	}

	//注册设置
	public function doSiteRegister() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			$this->db->save();
			m( 'Site' )->updateSiteCache();
			message( '修改会员注册设置成功', '', 'success' );
		}
		View::make( $this->template . '/register.html' );
	}

	//邮件通知设置
	public function doSiteMail() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			$this->db->save();
			m( 'Site' )->updateSiteCache();
			message( '修改会员注册设置成功', '', 'success' );
		}
		View::make( $this->template . '/mail.html' );
	}

	//支付设置
	public function doSitePay() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			$this->db->save();
			m( 'Site' )->updateSiteCache();
			message( '修改会员支付参数成功', 'back', 'success' );
		}
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', v( 'site.siteid' ) )->first();
		View::with( [ 'wechat' => $wechat ] );
		View::make( $this->template . '/pay.html' );
	}
}