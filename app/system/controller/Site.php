<?php namespace app\system\controller;

use houdunwang\request\Request;
use system\model\Modules;
use system\model\Package;
use system\model\SiteModules;
use system\model\SitePackage;
use system\model\SiteTemplate;
use system\model\SiteUser;
use system\model\SiteWechat;
use system\model\User;
use system\model\Site as SiteModel;

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
		     ->orderBy('siteid','DESC')
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

	/**
	 * 为站点设置权限
	 * 默认站点使用站长的权限
	 * 但我们可以为站点独立添加一些权限
	 * @return mixed
	 */
	public function access_setting() {
		//非系统管理员直接跳转到第四步,只有系统管理员可以设置用户扩展套餐与模块
		\User::superUserAuth();
		if ( IS_POST ) {
			//站点允许使用的空间大小
			$model                = SiteModel::find( SITEID );
			$model['allfilesize'] = Request::post( 'allfilesize' );
			$model->save();
			//删除站点旧的套餐
			SitePackage::where( 'siteid', SITEID )->delete();
			if ( $packageIds = Request::post( 'package_id', [ ] ) ) {
				foreach ( $packageIds as $id ) {
					SitePackage::insert( [ 'siteid' => SITEID, 'package_id' => $id, ] );
				}
			}
			//添加扩展模块
			SiteModules::where( 'siteid', SITEID )->delete();
			if ( $modules = Request::post( 'modules', [ ] ) ) {
				foreach ( $modules as $name ) {
					SiteModules::insert( [ 'siteid' => SITEID, 'module' => $name, ] );
				}
			}
			//添加扩展模板
			SiteTemplate::where( 'siteid', SITEID )->delete();
			if ( $templates = Request::post( 'templates', [ ] ) ) {
				foreach ( $templates as $name ) {
					SiteTemplate::insert( [ 'siteid' => SITEID, 'template' => $name, ] );
				}
			}
			//设置站长
			if ( $uid = Request::post( 'uid', 0, 'intval' ) ) {
				//删除站点用户信息
				SiteUser::where( 'siteid', SITEID )->where( 'role', 'owner' )->orWhere( 'uid', '=', $uid )->delete();
				//设置站点管理员
				SiteUser::insert( [ 'siteid' => SITEID, 'uid' => $uid, 'role' => 'owner' ] );
			}
			\Site::updateCache();
			message( '站点信息修改成功', 'with' );
		}

		//获取站长信息
		return view( 'access_setting' )->with( [
			'systemAllPackages' => \Package::getSystemAllPackageData(),
			'extPackage'        => \Package::getSiteExtPackageIds(),
			'defaultPackage'    => \Package::getSiteDefaultPackageIds(),
			'extModule'         => \Module::getSiteExtModules( SITEID ),
			'extTemplate'       => \Template::getSiteExtTemplates( SITEID ),
			'user'              => \User::getSiteOwner( SITEID ),
			'site'              => SiteModel::find( SITEID )
		] );
	}

	//设置站点微信公众号
	public function wechat() {
		//验证当前用户站点权限
		if ( ! \User::isManage() ) {
			message( '您不是网站管理员无法操作' );
		}
		switch ( $_GET['step'] ) {
			//修改微信公众号
			case 'wechat':
				//微信帐号管理
				if ( IS_POST ) {
					if ( $weid = SiteWechat::where( 'siteid', SITEID )->pluck( 'weid' ) ) {
						//编辑站点
						$SiteWechatModel = SiteWechat::find( $weid );
					} else {
						//新增公众号
						$SiteWechatModel = new SiteWechat();
					}
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
					$Site         = SiteModel::find( SITEID );
					$Site['weid'] = $weid;
					$Site->save();
					//更新站点缓存
					\Site::updateCache();
					go( u( 'wechat', [ 'step' => 'explain', 'siteid' => SITEID ] ) );
				}
				$wechat = SiteWechat::where( 'siteid', SITEID )->first();
				//更新站点缓存
				\Site::updateCache( SITEID );

				return view()->with( 'field', $wechat );
			case 'explain':
				//引导页面
				$wechat = SiteWechat::where( 'siteid', SITEID )->first();
				if ( $wechat ) {
					return view( 'explain' )->with( [ 'wechat' => $wechat ] );
				} else {
					message( '您还没有设置公众号信息', 'back', 'warning' );
				}
		}
	}

	//删除站点
	public function remove() {
		if ( \User::isManage() ) {
			\Site::remove( SITEID );
			Session::del( 'siteid' );
			message( '网站删除成功', 'with' );
		}
		message( '你不是站长不可以删除网站', 'with' );
	}

	//编辑站点
	public function edit() {
		if ( ! \User::isManage() ) {
			message( '你没有编辑站点的权限', 'with' );
		}
		if ( IS_POST ) {
			//更新站点数据
			$site                     = SiteModel::find( SITEID );
			$site['name']             = Request::post( 'name' );
			$site['description']      = Request::post( 'description' );
			$site['domain']           = Request::post( 'domain' );
			$site['module']           = Request::post( 'module' );
			$site['ucenter_template'] = Request::post( 'ucenter_template' );
			$site->save();
			\Site::updateCache();
			message( '网站数据保存成功', 'back', 'success' );
		}
		$site = SiteModel::where( 'siteid', SITEID )->first();

		return view()->with( [ 'site' => $site ] );
	}

	//添加站点
	public function addSite( SiteModel $site ) {
		//检测用户是否可以添加帐号
		if ( ! \User::hasAddSite() ) {
			message( '您可创建的站点数量已经用完,请联系管理员进行升级' );
		}
		if ( IS_POST ) {
			//添加站点信息
			$site['name']             = Request::post( 'name' );
			$site['description']      = Request::post( 'description' );
			$site['domain']           = Request::post( 'domain' );
			$site['module']           = Request::post( 'module' );
			$site['ucenter_template'] = 'default';
			$siteId                   = $site->save();
			//添加站长数据,系统管理员不添加数据
			\User::setSiteOwner( $siteId, v( 'user.info.uid' ) );
			//创建用户字段表数据
			\Site::InitializationSiteTableData( $siteId );
			//更新站点缓存
			\Site::updateCache( $siteId );
			message( '站点添加成功', 'lists' );
		}

		return view( 'site_setting' );
	}

	//公众号连接测试
	public function connect() {
		//与微信官网通信绑定验证
		$wechat = SiteWechat::where( 'siteid', SITEID )->first();
		c( "wechat", $wechat ? $wechat->toArray() : [ ] );
		$status = \WeChat::getAccessToken();
		SiteWechat::where( 'siteid', SITEID )->update( [ 'is_connect' => $status ? 1 : 0 ] );
		if ( $status ) {
			ajax( [ 'valid' => true, 'message' => '恭喜, 微信公众号接入成功' ] );
		} else {
			ajax( [ 'valid' => false, 'message' => '公众号接入失败' ] );
		}
	}

	/**
	 * 移除站长
	 * 只有系统管理员可以操作这个功能
	 */
	public function delOwner() {
		\User::superUserAuth();
		SiteUser::where( 'siteid', SITEID )->where( 'role', 'owner' )->delete();
		message( '删除站长成功', 'back', 'success' );
	}

	/**
	 * 获取拥有桌面主面访问的模块列表
	 */
	public function getModuleHasWebPage() {
		$modules = \Module::getModuleHasWebPage();

		return view()->with( 'modules', $modules );
	}
}