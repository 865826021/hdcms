<?php namespace system\service\build;

use system\model\MemberFields;

/**
 * 站点管理服务
 * Class Site
 * @package system\service\build
 */
class Site extends \system\model\Site {
	//加载站点缓存
	public function loadSite() {
		//缓存存在时不获取
		if ( v( 'site' ) ) {
			return TRUE;
		}
		//站点信息
		v( 'site.info', d( "site:" . SITEID ) );
		//站点设置
		v( 'site.setting', d( "setting:" . SITEID ) );
		//微信帐号
		v( 'site.wechat', d( "wechat:" . SITEID ) );
		//设置微信配置
		$config = [
			"token"          => v( 'wechat.token' ),
			"encodingaeskey" => v( 'wechat.encodingaeskey' ),
			"appid"          => v( 'wechat.appid' ),
			"appsecret"      => v( 'wechat.appsecret' ),
			"mch_id"         => v( 'setting.pay.weichat.mch_id' ),
			"key"            => v( 'setting.pay.weichat.key' ),
			"apiclient_cert" => v( 'setting.pay.weichat.apiclient_cert' ),
			"apiclient_key"  => v( 'setting.pay.weichat.apiclient_key' ),
			"rootca"         => v( 'setting.pay.weichat.rootca' ),
			"back_url"       => '',
		];
		//设置微信通信数据配置
		c( 'weixin', array_merge( c( 'weixin' ), $config ) );
		//设置邮箱配置
		c( 'mail', v( 'setting.smtp' ) );

		return TRUE;
	}

	/**
	 * 站点是否存在
	 *
	 * @param $siteId
	 *
	 * @return bool
	 */
	public function has( $siteId ) {
		return $this->where( 'siteid', $siteId )->get() ? TRUE : FALSE;
	}

	/**
	 * 初始化站点的会员字段信息数据
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return bool
	 */
	public function InitializationSiteTableData( $siteid ) {
		$memberField = new MemberFields();
		$memberField->where( 'siteid', $siteid )->delete();
		$profile_fields = Db::table( 'profile_fields' )->get();
		foreach ( $profile_fields as $f ) {
			$d['siteid']  = $siteid;
			$d['field']   = $f['field'];
			$d['title']   = $f['title'];
			$d['orderby'] = $f['orderby'];
			$d['status']  = $f['status'];
			$memberField->add( $d );
		}

		return TRUE;
	}

	/**
	 * 获取用户管理的所有站点信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return array 站点列表
	 */
	public function getUserAllSite( $uid ) {
		return $this->join( 'site_user', 'site.siteid', '=', 'site_user.siteid' )->where( 'site_user.uid', $uid )->get();
	}

	/**
	 * 更新站点数据缓存
	 *
	 * @param int $siteId 网站编号
	 *
	 * @return bool
	 */
	public function updateCache( $siteId = NULL ) {
		$siteId = $siteId ?: SITEID;
		//站点微信信息缓存
		$wechat         = Db::table( 'site_wechat' )->where( 'siteid', $siteId )->first();
		$data['wechat'] = $wechat ? $wechat->toArray() : [ ];
		//站点信息缓存
		$site         = Db::table( 'site' )->where( 'siteid', $siteId )->first();
		$data['site'] = $site ? $site->toArray() : [ ];
		//站点设置缓存
		$setting                     = Db::table( 'site_setting' )->where( 'siteid', $siteId )->first();
		$setting                     = $setting ? $setting->toArray() : [ ];
		$setting ['creditnames']     = unserialize( $setting['creditnames'] );
		$setting ['creditbehaviors'] = unserialize( $setting['creditbehaviors'] );
		$setting ['register']        = unserialize( $setting['register'] );
		$setting ['smtp']            = unserialize( $setting['smtp'] );
		$setting ['pay']             = unserialize( $setting['pay'] );
		$data['setting']             = $setting;
		//站点模块
		$data['modules'] = service( 'module' )->getSiteAllModules( $siteId, FALSE );
		foreach ( $data as $key => $value ) {
			d( "{$key}:{$siteId}", $value );
		}

		return TRUE;
	}

	/**
	 * 更新所有站点缓存
	 * @return bool
	 */
	public function updateAllCache() {
		foreach ( $this->lists( 'siteid' ) as $siteid ) {
			$this->updateCache( $siteid );
		}

		return TRUE;
	}

	/**
	 * 获取站点默认组
	 * @return mixed
	 */
	public function getDefaultGroup() {
		return $this->where( 'siteid', v( 'site.siteid' ) )->where( 'isdefault', 1 )->pluck( 'id' );
	}

	/**
	 * 获取站点所有组
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteGroups( $siteid = NULL ) {
		$siteid = $siteid ?: SITEID;

		return $this->where( 'siteid', $siteid )->get() ?: [ ];
	}

	/**
	 * 初始化站点的微信
	 *
	 * @param $siteid
	 *
	 * @return 微信表新增主键编号
	 */
	public function initSiteWeChat( $siteid ) {
		$data = [
			'siteid'     => $siteid,
			'wename'     => '',
			'account'    => '',
			'original'   => '',
			'level'      => 1,
			'appid'      => '',
			'appsecret'  => '',
			'qrcode'     => '',
			'icon'       => '',
			'is_connect' => 0,
		];

		return $this->insertGetId( $data );
	}
}