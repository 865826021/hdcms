<?php namespace app\system\controller;

use system\model\Modules;
use system\model\Package;
use system\model\SiteWechat;
use system\model\User;

/**
 * Class Site
 * @package app\system\controller
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Site {
	public function __construct() {
		//登录检测
		\User::loginAuth();
	}

	/**
	 * 站点列表
	 *
	 * @param \system\model\Site $site
	 * @param User $user
	 *
	 * @return mixed
	 */
	public function lists( \system\model\Site $site, User $user ) {
		$user = $user->find( v( "user.info.uid" ) );
		$site->field( 'site.siteid,site.name,user.starttime,endtime,site_wechat.icon,site_wechat.is_connect' )
		     ->leftJoin( 'site_user', 'site.siteid', '=', 'site_user.siteid' )
		     ->leftJoin( 'user', 'site_user.uid', '=', 'user.uid' )
		     ->leftJoin( 'site_wechat', 'site.siteid', '=', 'site_wechat.siteid' )
		     ->groupBy( 'site.siteid' );
		//按网站名称搜索
		if ( $sitename = q( 'post.sitename' ) ) {
			$site->where( 'site.name', 'like', "%{$sitename}%" );
		}
		//按网站域名搜索
		if ( $domain = q( 'post.domain' ) ) {
			$site->where( 'site.domain', 'like', "%{$domain}%" );
		}
		//普通站长获取站点列表
		if ( ! $isSuperUser = \User::isSuperUser() ) {
			$site->where( 'user.uid', v( 'user.info.uid' ) );
		}
		if ( $sites = $site->get() ) {
			//获取站点套餐与所有者数据
			foreach ( $sites as $k => $v ) {
				$v['package'] = \Package::getSiteAllPackageData( $v['siteid'] );
				$v['owner']   = \User::getSiteOwner( $v['siteid'] );
				if ( ! empty( $v['owner'] ) ) {
					$v['owner']['group_name'] = Db::table( 'user_group' )->where( 'id', $v['owner']['groupid'] )->pluck( 'name' );
				}
				$sites[ $k ] = $v;
			}
		}

		return view()->with( [ 'sites' => $sites, 'user' => $user ] );
	}

	//网站列表页面,获取站点包信息
	public function package() {
		//根据站长所在会员组获取套餐
		$packageModel = new Package();
		$pids         = $packageModel->getSiteAllPackageIds();
		$package      = [ ];
		if ( in_array( - 1, $pids ) ) {
			$package[] = "所有服务";
		} else if ( ! empty( $pids ) ) {
			$package = Db::table( 'package' )->whereIn( 'id', $pids )->lists( 'name' );
		}
		//获取模块
		$modulesModel = new Modules();
		$modules      = $modulesModel->getSiteAllModules();
		ajax( [ 'package' => $package, 'modules' => $modules ] );
	}

	//添加站点
	public function addSite( \system\model\Site $site ) {
		//检测用户是否可以添加帐号
		if ( ! \User::hasAddSite() ) {
			message( '您可创建的站点数量已经用完,请联系管理员进行升级' );
		}
		if ( IS_POST ) {
			//添加站点信息
			$site['name']        = Request::post( 'name' );
			$site['description'] = Request::post( 'description' );
			$site['domain']      = Request::post( 'domain' );
			$site['module']      = Request::post( 'module' );
			$siteId              = $site->save();
			//添加站长数据,系统管理员不添加数据
			\User::setSiteOwner( $siteId, v( 'user.info.uid' ) );
			//创建用户字段表数据
			\Site::InitializationSiteTableData( $siteId );
			//更新站点缓存
			\Site::updateCache( $siteId );
			message( '站点添加成功', 'lists', 'error' );
		}

		return view( 'site_setting' );
	}

	//权限设置
	public function access_setting() {
		//非系统管理员直接跳转到第四步,只有系统管理员可以设置用户扩展套餐与模块
		service( 'user' )->superUserAuth();
		$Site = new \system\model\Site();
		if ( IS_POST ) {
			//站点允许使用的空间大小
			Db::table( 'site' )->where( 'siteid', SITEID )->update( [ 'allfilesize' => q( 'post.allfilesize', 200, 'intval' ) ] );
			//删除站点旧的套餐
			Db::table( 'site_package' )->where( 'siteid', SITEID )->delete();
			if ( $package_id = q( 'post.package_id', [ ] ) ) {
				foreach ( $package_id as $id ) {
					Db::table( 'site_package' )->insert( [ 'siteid' => SITEID, 'package_id' => $id, ] );
				}
			}
			//添加扩展模块
			Db::table( 'site_modules' )->where( 'siteid', SITEID )->delete();
			if ( $modules = q( 'post.modules', [ ] ) ) {
				foreach ( $modules as $name ) {
					Db::table( 'site_modules' )->insert( [ 'siteid' => SITEID, 'module' => $name, ] );
				}
			}
			//添加扩展模板
			Db::table( 'site_template' )->where( 'siteid', SITEID )->delete();
			if ( $templates = q( 'post.templates', [ ] ) ) {
				foreach ( $templates as $name ) {
					Db::table( 'site_template' )->insert( [ 'siteid' => SITEID, 'template' => $name, ] );
				}
			}
			//设置站长
			if ( $manage_id = q( 'post.uid', 0, 'intval' ) ) {
				//删除站点用户信息
				Db::table( 'site_user' )->where( 'siteid', SITEID )->where( 'role', 'owner' )->orWhere( 'uid', '=', $manage_id )->delete();
				//设置站点管理员
				Db::table( 'site_user' )->insert( [ 'siteid' => SITEID, 'uid' => $manage_id, 'role' => 'owner' ] );
			}
			service( 'site' )->updateCache();
			message( '站点信息修改成功', 'lists', 'success' );
		}
		//获取站长信息
		$user = service( 'user' )->getSiteOwner( SITEID );
		//获取系统所有套餐
		$systemAllPackages = service( 'package' )->getSystemAllPackageData();
		//扩展模块
		$extModule   = service( 'module' )->getSiteExtModules( SITEID );
		$extTemplate = service( 'template' )->getSiteExtTemplates( SITEID );

		return view( 'access_setting' )->with( [
			'systemAllPackages' => $systemAllPackages,
			'extPackage'        => service( 'package' )->getSiteExtPackageIds( SITEID ),
			'defaultPackage'    => service( 'package' )->getSiteDefaultPackageIds( SITEID ),
			'extModule'         => $extModule,
			'extTemplate'       => $extTemplate,
			'user'              => $user,
			'site'              => $Site->find( SITEID )
		] );
	}

	//设置站点微信公众号
	public function wechat() {
		switch ( $_GET['step'] ) {
			case 'add':
				//验证当前用户站点权限
				if ( ! service( 'user' )->isOwner() ) {
					message( '您不是网站管理员无法操作' );
				}
				//微信帐号管理
				if ( IS_POST ) {
					$SiteWechatModel              = new SiteWechat();
					$SiteWechatModel['siteid']    = SITEID;
					$SiteWechatModel['wename']    = Request::post( 'wename' );
					$SiteWechatModel['account']   = Request::post( 'account' );
					$SiteWechatModel['original']  = Request::post( 'original' );
					$SiteWechatModel['level']     = Request::post( 'level' );
					$SiteWechatModel['appid']     = Request::post( 'appid' );
					$SiteWechatModel['appsecret'] = Request::post( 'appsecret' );
					$SiteWechatModel['qrcode']    = Request::post( 'qrcode' );
					$SiteWechatModel['icon']      = Request::post( 'icon' );
					$weid                         = $SiteWechatModel->save();
					//设置站点微信记录编号
					$Site           = new \system\model\Site();
					$Site['siteid'] = SITEID;
					$Site['weid']   = $weid;
					$Site->save();
					//更新站点缓存
					service( 'site' )->updateCache();
					go( u( 'wechat', [ 'step' => 'explain', 'siteid' => SITEID ] ) );
				}
				$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();
				View::with( 'field', $wechat );

				return view( 'post_weixin' );
			case 'explain':
				//验证当前用户站点权限
				if ( ! service( 'user' )->isOwner( SITEID ) ) {
					message( '你没有管理该站点的权限' );
				}
				//更新站点缓存
				service( 'site' )->updateCache( SITEID );
				//引导页面
				$wechat = model( 'SiteWechat' )->where( 'siteid', SITEID )->first();

				return view( 'explain' )->with( [ 'wechat' => $wechat ] );
		}
	}

	//删除站点
	public function remove() {
		$Site = new \system\model\Site();
		if ( service( 'user' )->isManage() ) {
			$Site->remove( SITEID );
			Session::del( 'siteid' );
			message( '网站删除成功', 'back', 'success' );
		}
		message( '你不是站长不可以删除网站', 'back', 'error' );
	}

	//编辑站点
	public function edit() {
		if ( ! service( 'user' )->isManage() ) {
			message( '你没有编辑站点的权限', 'back', 'success' );
		}

		if ( IS_POST ) {
			//更新站点数据
			$site              = ( new \system\model\Site() )->find( SITEID );
			$site->name        = Request::post( 'name' );
			$site->description = Request::post( 'description' );
			$site->domain      = Request::post( 'domain' );
			$site->module      = Request::post( 'module' );
			$site->save();
			//更新微信数据
			Db::table( 'site_wechat' )->where( 'siteid', SITEID )->update( Request::post() );
			//测试连接
			$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();
			c( "weixin", $wechat );
			//与微信官网通信绑定验证
			$status = \Weixin::getAccessToken( '', true );
			Db::table( 'site_wechat' )->where( 'siteid', SITEID )->update( [ 'is_connect' => $status ? 1 : 0 ] );
			if ( $status ) {
				message( '恭喜, 公众号连接成功', 'lists', 'success' );
			} else {
				message( '公众号连接失败,请查看配置项', 'back', 'error' );
			}
		}
		$site   = Db::table( 'site' )->where( 'siteid', SITEID )->first();
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();

		return view()->with( [ 'site' => $site, 'wechat' => $wechat ] );
	}

	//公众号连接测试
	public function connect() {
		//与微信官网通信绑定验证
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();
		c( "weixin", $wechat );
		$status = Weixin::getAccessToken();
		Db::table( 'site_wechat' )->where( 'siteid', SITEID )->update( [ 'is_connect' => $status ? 1 : 0 ] );
		if ( $status ) {
			ajax( [ 'valid' => true, 'message' => '恭喜, 微信公众号接入成功' ] );
		} else {
			ajax( [ 'valid' => false, 'message' => '公众号接入失败' ] );
		}
	}

	//移除站长
	public function delOwner() {
		service( 'user' )->superUserAuth();
		model( 'SiteUser' )->remove( SITEID );
		message( '删除站长成功', 'back', 'success' );
	}
}