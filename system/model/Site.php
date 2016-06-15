<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\model;

//站点服务
use hdphp\model\Model;

class Site extends Model {
	protected $table = 'site';
	protected $validate
	                 = [
			[ 'name', 'required', '站点名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'name', 'unique', '站点名称已经存在', self::MUST_VALIDATE, self::MODEL_INSERT ],
		];
	protected $auto
	                 = [
			[ 'icp', '', 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'weid', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'allfilesize', 200, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
		];
	//站点关联表(删除站点时使用)
	protected $tables
		= [
			'site',//站点表
			'user_permission',//用户权限分配
			'site_package',//站点套餐
			'web_category',//栏目
			'web_nav',//导航
			'web_article',//文章
			'web_page',//微官网页面(快捷导航/专题页面)
			'web_slide',//站点幻灯图
			'site_user',//站点操作员
			'site_modules',//站点模块
			'site_wechat',//微信
			'site_setting',//站点设置
			'member_group',//会员组
			'member_fields',//会员字段
		];

	//加载站点缓存
	public function loadSite() {
		$siteid = Session::get( 'siteid' );
		if ( empty( $siteid ) ) {
			return FALSE;
		}
		//缓存存在时不进行获取
		if ( v( 'site' ) ) {
			return TRUE;
		}
		//微信帐号
		v( 'wechat', d( "wechat:{$siteid}" ) );
		//站点信息
		v( 'site', d( "site:{$siteid}" ) );
		//站点设置
		v( 'setting', d( "setting:{$siteid}" ) );
		//加载模块
		v( 'modules', d( "modules:{$siteid}" ) );
		//设置微信配置
		$config = [
			"token"          => v( 'wechat.token' ),
			"encodingaeskey" => v( 'wechat.encodingaeskey' ),
			"appid"          => v( 'wechat.appid' ),
			"appsecret"      => v( 'wechat.appsecret' ),
			"mch_id"         => v( 'setting.mch_id' ),
			"key"            => v( 'setting.key' ),
			"notify_url"     => __ROOT__ . '/index.php/wxnotifyurl',
			"back_url"       => '',
			"apiclient_cert" => v( 'setting.apiclient_cert' ),
			"apiclient_key"  => v( 'setting.apiclient_key' ),
			"rootca"         => v( 'setting.rootca' ),
		];
		c( 'weixin', array_merge( c( 'weixin' ), $config ) );

		return TRUE;
	}

	/**
	 * 更新站点数据缓存
	 *
	 * @param int $siteid 网站编号
	 *
	 * @return bool
	 */
	public function updateSiteCache( $siteid ) {
		//站点微信信息缓存
		$data['wechat'] = Db::table( 'site_wechat' )->where( 'siteid', '=', $siteid )->first();
		//站点信息缓存
		$data['site'] = Db::table( 'site' )->where( 'siteid', '=', $siteid )->first();
		//站点设置缓存
		$data['setting'] = Db::table( 'site_setting' )->where( 'siteid', '=', $siteid )->first();
		//站点模块
		$data['modules'] = $this->modules( $siteid );
		foreach ( $data as $key => $value ) {
			d( "{$key}:{$siteid}", $value );
		}

		return TRUE;
	}

	/**
	 * 删除(注销)站点
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return bool
	 */
	public function remove( $siteid ) {
		foreach ( $this->tables as $t ) {
			Db::table( $t )->where( 'siteid', $siteid )->delete();
		}
		//删除缓存
		$keys = [ 'access', 'setting', 'wechat', 'site', 'modules', 'module_binding' ];
		foreach ( $keys as $key ) {
			d( "{$key}:{$siteid}", '[del]' );
		}
		Session::del( 'siteid' );

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
		return $this->join('site_user','site.siteid','=','site_user.siteid')->where('site_user.uid',$uid)->get();
	}
}