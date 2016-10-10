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
		$this->id = $this->db->where( 'siteid', SITEID )->pluck( 'id' );
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
			$this->db->save($_POST);
			service( 'site' )->updateCache();
			message( '积分设置成功', '', 'success' );
		}
		View::with( 'creditnames', v( 'site.setting.creditnames' ) );

		return view( $this->template . '/credit.html' );
	}

	//积分策略
	public function doSiteTactics() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			$this->db->save($_POST);
			service( 'site' )->updateCache();
			message( '积分策略更新成功', '', 'success' );
		}

		return view( $this->template . '/tactics.html' );
	}

	//注册设置
	public function doSiteRegister() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			$this->db->save($_POST);
			service( 'site' )->updateCache();
			message( '修改会员注册设置成功', '', 'success' );
		}

		return view( $this->template . '/register.html' );
	}

	//邮件通知设置
	public function doSiteMail() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			$this->db->save($_POST);
			service( 'site' )->updateCache();
			//发送测试邮件
			if ( v( 'setting.smtp.testing' ) ) {
				$d = Mail::send( v( 'setting.smtp.testusername' ), v( 'setting.smtp.testusername' ), "邮箱配置测试", '恭喜!邮箱配置成功' );
				if ( $d ) {
					message( "邮箱设置成功,测试邮件发送成功", 'refresh', 'success', 3 );
				} else {
					message( "邮箱配置保存成功,测试邮件发送失败", 'refresh', 'error', 3 );
				}
			}
			message( '邮箱设置成功', 'refresh', 'success' );
		}

		return view( $this->template . '/mail.html' );
	}

	//支付设置
	public function doSitePay() {
		if ( IS_POST ) {
			$_POST['id'] = $this->id;
			//批量插入
			$this->db->save( $_POST );
			service( 'site' )->updateCache();
			message( '修改会员支付参数成功', 'back', 'success' );
		}
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', v( 'site.siteid' ) )->first();

		return view( $this->template . '/pay.html' )->with( [ 'wechat' => $wechat ] );
	}
}