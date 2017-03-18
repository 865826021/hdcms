<?php namespace app\site\controller;

use houdunwang\request\Request;
use system\model\SiteSetting;

/**
 * 网站配置管理
 * Class Setting
 * @package module\setting\controller
 */
class Setting {
	protected $db;
	//主键
	protected $id;

	public function __construct() {
		auth();
		$this->id = SiteSetting::where( 'siteid', SITEID )->pluck( 'id' );
		$this->db = $this->id ? SiteSetting::find( $this->id ) : new SiteSetting();
	}

	//积分设置
	public function credit() {
		if ( IS_POST ) {
			//积分/余额必须开启
			$_POST['creditnames']['credit1']['status'] = 1;
			$_POST['creditnames']['credit2']['status'] = 1;
			foreach ( $_POST['creditnames'] as $credit => $d ) {
				$_POST['creditnames'][ $credit ]['status'] = isset( $d['status'] ) ? intval( $d['status'] ) : 0;
			}
			$this->db['creditnames'] = $_POST['creditnames'];
			$this->db->save();
			\Site::updateCache();
			message( '积分设置成功', '', 'success' );
		}
		View::with( 'creditnames', v( 'site.setting.creditnames' ) );

		return view();
	}

	//积分策略
	public function tactics() {
		if ( IS_POST ) {
			$this->db['creditbehaviors'] = Request::post( 'creditbehaviors' );
			$this->db->save();
			\Site::updateCache();
			message( '积分策略更新成功', '', 'success' );
		}

		return view();
	}

	//注册设置
	public function register() {
		if ( IS_POST ) {
			$this->db['register']       = Request::post( 'register' );
			$this->db['username_login'] = Request::post( 'username_login' );
			$this->db['oauth_login']    = json_encode( Request::post( 'oauth_login' ) );
			$this->db['wechat_login']   = Request::post( 'wechat_login' );
			$this->db->save();
			\Site::updateCache();
			message( '修改会员注册设置成功', '', 'success' );
		}

		return view();
	}

	//邮件通知设置
	public function mail() {
		if ( IS_POST ) {
			$this->db['smtp'] = Request::post( 'smtp' );
			$this->db->save();
			\Site::updateCache();
			//发送测试邮件
			if ( v( 'site.setting.smtp.testing' ) ) {
				$d = Mail::send( v( 'setting.smtp.testusername' ), v( 'setting.smtp.testusername' ), "邮箱配置测试", '恭喜!邮箱配置成功' );
				if ( $d ) {
					message( "测试邮件发送成功", 'refresh', 'success', 3 );
				} else {
					message( "测试邮件发送失败", 'refresh', 'error', 3 );
				}
			}
			message( '邮箱配置保存成功', 'refresh', 'success' );
		}

		return view();
	}

	//支付设置
	public function pay() {
		if ( IS_POST ) {
			$this->db['id']  = $this->id;
			$this->db['pay'] = Request::post( 'pay' );
			$this->db->save();
			\Site::updateCache();
			message( '修改会员支付参数成功', 'back', 'success' );
		}
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', siteid() )->first();

		return view()->with( [ 'wechat' => $wechat ] );
	}
}