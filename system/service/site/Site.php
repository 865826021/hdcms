<?php namespace system\service\site;

use system\model\MemberFields;
use system\model\MemberGroup;
use system\model\SiteSetting;
use system\service\Common;
use system\model\Site as SiteModel;

/**
 * 服务功能类
 * Class Site
 * @package system\service\site
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Site extends Common {
	/**
	 * 系统启动时执行的站点信息初始化
	 */
	public function siteInitialize() {
		/**
		 * 站点编号
		 * 如果GET中存在siteid使用,否则使用SESSION会话中的siteid
		 */
		if ( $siteId = Request::get( 'siteid' ) ) {
			if ( Db::table( 'site' )->find( $siteId ) ) {
				define( 'SITEID', $siteId );
				$this->loadSite( $siteId );
			} else {
				message( '你访问的站点不存在', 'back', 'error' );
			}
		}
	}

	/**
	 * 加载当前请求的站点缓存
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool|void
	 */
	public function loadSite( $siteId ) {
		//缓存存在时不获取
		if ( v( 'site' ) || empty( $siteId ) ) {
			return;
		}

		//站点信息
		v( 'site.info', d( "site:" . $siteId ) );
		//站点设置
		v( 'site.setting', d( "setting:" . $siteId ) );
		//微信帐号
		v( 'site.wechat', d( "wechat:" . $siteId ) );
		//加载模块
		v( 'site.modules', d( "modules:" . $siteId ) );
		//设置微信配置
		$config = [
			"token"          => v( 'site.wechat.token' ),
			"encodingaeskey" => v( 'site.wechat.encodingaeskey' ),
			"appid"          => v( 'site.wechat.appid' ),
			"appsecret"      => v( 'site.wechat.appsecret' ),
			"mch_id"         => v( 'site.setting.pay.wechat.mch_id' ),
			"key"            => v( 'site.setting.pay.wechat.key' ),
			"apiclient_cert" => v( 'site.setting.pay.wechat.apiclient_cert' ),
			"apiclient_key"  => v( 'site.setting.pay.wechat.apiclient_key' ),
			"rootca"         => v( 'site.setting.pay.wechat.rootca' ),
			"back_url"       => ''
		];
		//设置微信通信数据配置
		c( 'wechat', array_merge( c( 'wechat' ), $config ) );
		//设置邮箱配置
		c( 'mail', v( 'site.setting.smtp' ) );
		//会员中心默认风格
		define( '__UCENTER_TEMPLATE__', v( 'site.info.ucenter_template' ) );

		return true;
	}

	/**
	 * 删除站点
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool
	 */
	public function remove( $siteId ) {
		/**
		 * 删除所有包含siteid的表
		 * 因为siteid在系统中是站点编号
		 */
		$tables = \Schema::getAllTableInfo();
		foreach ( $tables['table'] as $name => $info ) {
			$table = str_replace( c( 'database.prefix' ), '', $name );
			//表中存在siteid字段时操作这个表
			if ( \Schema::fieldExists( 'siteid', $table ) ) {
				Db::table( $table )->where( 'siteid', $siteId )->delete();
			}
		}
		//删除缓存
		$keys = [ 'access', 'setting', 'wechat', 'site', 'modules', 'module_binding' ];
		foreach ( $keys as $key ) {
			d( "{$key}:{$siteId}", '[del]' );
		}

		return true;
	}

	/**
	 * 站点是否存在
	 *
	 * @param $siteId
	 *
	 * @return bool
	 */
	public function has( $siteId ) {
		return $this->where( 'siteid', $siteId )->get() ? true : false;
	}

	/**
	 * 新建站点时初始化站点的默认数据
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool
	 */
	public function InitializationSiteTableData( $siteId ) {
		/*
		|--------------------------------------------------------------------------
		| 站点设置
		|--------------------------------------------------------------------------
		*/
		$SiteSetting                = new SiteSetting();
		$SiteSetting['siteid']      = $siteId;
		$SiteSetting['quickmenu']   = 1;
		$SiteSetting['creditnames'] = [
			'credit1' => [ 'title' => '积分', 'status' => 1 ],
			'credit2' => [ 'title' => '余额', 'status' => 1 ],
			'credit3' => [ 'title' => '', 'status' => 0 ],
			'credit4' => [ 'title' => '', 'status' => 0 ],
			'credit5' => [ 'title' => '', 'status' => 0 ],
		];
		//注册设置
		$SiteSetting['register'] = [
			'focusreg' => 0,
			'item'     => 2
		];
		//积分策略
		$SiteSetting['creditbehaviors'] = [
			'activity' => 'credit1',
			'currency' => 'credit2'
		];
		$SiteSetting->save();

		/*
		|--------------------------------------------------------------------------
		| 站点会员组设置
		|--------------------------------------------------------------------------
		*/
		$MemberGroup              = new MemberGroup();
		$MemberGroup['siteid']    = $siteId;
		$MemberGroup['title']     = '会员';
		$MemberGroup['isdefault'] = 1;
		$MemberGroup['is_system'] = 1;
		$MemberGroup->save();

		/*
		|--------------------------------------------------------------------------
		| 创建用户字段表数据
		|--------------------------------------------------------------------------
		*/
		$memberField = new MemberFields();
		$memberField->where( 'siteid', $siteId )->delete();
		$profile_fields = Db::table( 'profile_fields' )->get();
		foreach ( $profile_fields as $f ) {
			$d['siteid']  = $siteId;
			$d['field']   = $f['field'];
			$d['title']   = $f['title'];
			$d['orderby'] = $f['orderby'];
			$d['status']  = $f['status'];
			$memberField->insert( $d );
		}

		/*
		|--------------------------------------------------------------------------
		| 初始快捷菜单
		|--------------------------------------------------------------------------
		*/
		$data['siteid'] = $siteId;
		$data['data']   = '{"status":1,"system":[],"module":[]}';
		$data['uid']    = v( 'user.info.uid' );
		Db::table( 'site_quickmenu' )->insert( $data );

		/*
		|--------------------------------------------------------------------------
		| 初始文章系统表
		|--------------------------------------------------------------------------
		*/
		service( 'article.init.make', $siteId );

		return true;
	}

	/**
	 * 获取用户管理的所有站点信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return array 站点列表
	 */
	public function getUserAllSite( $uid ) {
		return SiteModel::join( 'site_user', 'site.siteid', '=', 'site_user.siteid' )
		                ->where( 'site_user.uid', $uid )->get();
	}

	/**
	 * 更新站点数据缓存
	 *
	 * @param int $siteId 网站编号
	 *
	 * @return bool
	 */
	public function updateCache( $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;
		//站点微信信息缓存
		$wechat         = Db::table( 'site_wechat' )->where( 'siteid', $siteId )->first();
		$data['wechat'] = $wechat ?: [];
		//站点信息缓存
		$site         = Db::table( 'site' )->where( 'siteid', $siteId )->first();
		$data['site'] = $site ?: [];
		//站点设置缓存
		$setting                     = Db::table( 'site_setting' )->where( 'siteid', $siteId )->first();
		$setting                     = $setting ?: [];
		$setting ['creditnames']     = json_decode( $setting['creditnames'], true );
		$setting ['creditbehaviors'] = json_decode( $setting['creditbehaviors'], true );
		$setting ['register']        = json_decode( $setting['register'], true );
		$setting ['oauth_login']     = json_decode( $setting['oauth_login'], true );
		$setting ['smtp']            = json_decode( $setting['smtp'], true );
		$setting ['pay']             = json_decode( $setting['pay'], true );
		$setting ['quickmenu']       = $setting['quickmenu'];
		$data['setting']             = $setting;
		//站点模块
		$data['modules'] = \Module::getSiteAllModules( $siteId, false );
		foreach ( $data as $key => $value ) {
			d( "{$key}:{$siteId}", $value, 0, [ 'siteid' => $siteId ] );
		}

		return true;
	}

	/**
	 * 更新所有站点缓存
	 * @return bool
	 */
	public function updateAllCache() {
		foreach ( (array) SiteModel::lists( 'siteid' ) as $siteid ) {
			$this->updateCache( $siteid );
		}

		return true;
	}

	/**
	 * 获取站点默认组
	 * @return mixed
	 */
	public function getDefaultGroup() {
		return Db::table( 'member_group' )->where( 'siteid', v( 'site.siteid' ) )->where( 'isdefault', 1 )->pluck( 'id' );
	}

	/**
	 * 获取站点所有组
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteGroups( $siteid = null ) {
		$siteid = $siteid ?: SITEID;

		return Db::table( 'member_group' )->where( 'siteid', $siteid )->get() ?: [];
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