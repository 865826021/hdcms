<?php namespace app\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\MemberFields;
use system\model\MemberGroup;
use system\model\Modules;
use system\model\Package;
use system\model\SiteModules;
use system\model\SitePackage;
use system\model\SiteSetting;
use system\model\SiteTemplate;
use system\model\SiteUser;
use system\model\SiteWechat;
use system\model\User;
use system\model\UserPermission;

/**
 * 站点管理
 * Class site
 * @package system\controller
 */
class Site {

	public function __construct() {
		//登录检测
		( new User() )->isLogin();
	}

	//站点列表
	public function lists() {
		$site    = new \system\model\Site();
		$user    = ( new User() )->find( v( "user.uid" ) );
		$package = new Package();
		//加载当前操作的站点缓存
		$site->loadSite();
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
		if ( ! $isSuperUser = $user->isSuperUser( v( 'user.uid' ), 'return' ) ) {
			$site->where( 'user.uid', v( 'user.uid' ) );
		}
		$sites = $site->get()->toArray();
		//获取站点套餐与所有者数据
		foreach ( $sites as $k => $v ) {
			$v['package'] = $package->getSiteAllPackageData( $v['siteid'] );
			$v['owner']   = $user->getSiteOwner( $v['siteid'] );
			if ( ! empty( $v['owner'] ) ) {
				$v['owner']['group_name'] = Db::table( 'user_group' )->where( 'id', $v['owner']['groupid'] )->pluck( 'name' );
			}

			$sites[ $k ] = $v;
		}

		return view()->with( [ 'sites' => $sites, 'user' => $user ] );
	}

	//网站列表页面,获取站点包信息
	public function package() {
		//根据站长所在会员组获取套餐
		$packageModel = new Package();
		$pids         = $packageModel->getSiteAllPackageIds( SITEID );
		$package      = [ ];
		if ( in_array( - 1, $pids ) ) {
			$package[] = "所有服务";
		} else if ( ! empty( $pids ) ) {
			$package = Db::table( 'package' )->whereIn( 'id', $pids )->lists( 'name' );
		}
		//获取模块
		$modulesModel = new Modules();
		$modules      = $modulesModel->getSiteAllModules( SITEID );
		ajax( [ 'package' => $package, 'modules' => $modules ] );
	}

	//添加站点
	public function post() {
		$User = new User;
		$Site = new \system\model\Site();
		switch ( $_GET['step'] ) {
			//设置站点
			case 'site_setting':
				if ( IS_POST ) {
					//添加站点信息
					if ( ! $siteid = $Site->add( $_POST ) ) {
						message( $Site->getError(), 'back', 'error' );
					}
					//添加站长数据,系统管理员不添加数据
					( new SiteUser() )->setSiteOwner( $siteid, Session::get( 'user.uid' ) );
					//初始站点配置
					$setting = [
						'siteid'          => $siteid,
						'creditnames'     => [
							'credit1' => [ 'title' => '积分', 'status' => 1 ],
							'credit2' => [ 'title' => '余额', 'status' => 1 ],
							'credit3' => [ 'title' => '', 'status' => 0 ],
							'credit4' => [ 'title' => '', 'status' => 0 ],
							'credit5' => [ 'title' => '', 'status' => 0 ],
						],
						'register'        => [
							'focusreg' => 0,
							'item'     => 2
						],
						'creditbehaviors' => [
							'activity' => 'credit1',
							'currency' => 'credit2'
						]
					];
					( new SiteSetting() )->add( $setting );
					//添加默认会员组
					( new MemberGroup() )->add( [ 'siteid' => $siteid, 'title' => '会员', 'isdefault' => 1, 'is_system' => 1 ] );
					//创建用户字段表数据
					( new MemberFields() )->InitializationSiteTableData( $siteid );
					//更新站点缓存
					$Site->updateSiteCache( $siteid );
					go( u( 'post', [ 'step' => 'wechat', 'siteid' => $siteid ] ) );
				}

				return view( 'site_setting' );
			//设置公众号
			case 'wechat':
				$UserPermissionModel = new UserPermission();
				//验证当前用户站点权限
				if ( ! $User->isOwner( SITEID ) ) {
					message( '您不是网站管理员无法操作' );
				}
				//微信帐号管理
				if ( IS_POST ) {
					$SiteWechatModel = new SiteWechat();
					$_POST['siteid'] = SITEID;
					if ( ! $weid = $SiteWechatModel->add( $_POST ) ) {
						message( $SiteWechatModel->getError(), 'back', 'error' );
					}
					//设置站点微信记录编号
					$data = [ 'siteid' => SITEID, 'weid' => $weid ];
					if ( ! $Site->save( $data ) ) {
						message( $Site->getError(), 'back', 'error' );
					}
					//更新站点缓存
					$Site->updateSiteCache( SITEID );
					go( u( 'post', [ 'step' => 'access_setting', 'siteid' => SITEID ] ) );
				}

				return view( 'post_weixin' );
			//设置权限,只有系统管理员可以操作
			case 'access_setting':
				//非系统管理员直接跳转到第四步,只有系统管理员可以设置用户扩展套餐与模块
				$User->isSuperUser( v( 'user.uid' ), 'return' );
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
					$Site->updateSiteCache( SITEID );
					if ( $from_url = q( 'get.from' ) ) {
						//有来源地址,比如从站点列表进入
						message( '站点信息修改成功', $from_url, 'success' );
					} else {
						go( u( 'post', [ 'step' => 'explain', 'siteid' => SITEID ] ) );
					}
				}
				//获取站长信息
				$user         = ( new SiteUser() )->getSiteOwner( SITEID );
				$packageModel = new Package();
				//获取系统所有套餐
				$systemAllPackages = $packageModel->getSystemAllPackageData();
				//扩展模块
				$extModule   = ( new SiteModules() )->getSiteExtModules( SITEID );
				$extTemplate = ( new SiteTemplate() )->getSiteExtTemplates( SITEID );
				return view( 'access_setting' )->with( [
					'systemAllPackages' => $systemAllPackages,
					'extPackage'        => $packageModel->getSiteExtPackageIds( SITEID ),
					'defaultPackage'    => $packageModel->getSiteDefaultPackageIds( SITEID ),
					'extModule'         => $extModule,
					'extTemplate'       => $extTemplate,
					'user'              => $user,
					'site'              => $Site->find( SITEID )
				] );
			case 'explain':
				//验证当前用户站点权限
				if ( ! $User->isOwner( SITEID ) ) {
					message( '你没有管理该站点的权限' );
				}
				//更新站点缓存
				$Site->updateSiteCache( SITEID );
				//引导页面
				$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();

				return view( 'explain' )->with( [ 'wechat' => $wechat ] );
		}
	}

	//删除站点
	public function remove() {
		$User = new User();
		$Site = new \system\model\Site();
		if ( $User->isManage( SITEID ) ) {
			$Site->remove( SITEID );
			message( '网站删除成功', 'back', 'success' );
		}
		message( '你不是站长不可以删除网站', 'back', 'error' );
	}

	//编辑站点
	public function edit() {
		$User = new User();
		$Site = new \system\model\Site();
		if ( ! $User->isManage( SITEID ) ) {
			message( '你没有编辑站点的权限', 'back', 'success' );
		}
		if ( IS_POST ) {
			$_POST['siteid'] = SITEID;
			$Site->updateSiteCache( SITEID );
			if ( ! $Site->save( $_POST ) ) {
				message( $Site->getError(), 'back', 'error' );
			}
			//更新站点数据
			$Site->save( $_POST );
			//更新微信数据
			Db::table( 'site_wechat' )->where( 'siteid', SITEID )->update( $_POST );
			//测试连接
			$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();
			c( "weixin", $wechat );
			//与微信官网通信绑定验证

			$status = \Weixin::getAccessToken( '', TRUE );
			Db::table( 'site_wechat' )->where( 'siteid', SITEID )->update( [ 'is_connect' => $status ? 1 : 0 ] );
			if ( $status ) {
				message( '恭喜, 公众号连接成功', 'lists', 'success' );
			} else {
				message( '公众号连接失败,请查看配置项', 'back', 'error' );
			}
		}
		$site   = Db::table( 'site' )->where( 'siteid', SITEID )->first();
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();
		View::with( [ 'site' => $site, 'wechat' => $wechat ] )->make();
	}

	//公众号连接测试
	public function connect() {
		//与微信官网通信绑定验证
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', SITEID )->first();
		c( "weixin", $wechat );
		$status = Weixin::getAccessToken();
		Db::table( 'site_wechat' )->where( 'siteid', SITEID )->update( [ 'is_connect' => $status ? 1 : 0 ] );
		if ( $status ) {
			ajax( [ 'valid' => TRUE, 'message' => '恭喜, 微信公众号接入成功' ] );
		} else {
			ajax( [ 'valid' => FALSE, 'message' => '公众号接入失败' ] );
		}
	}

	public function delOwner() {
		$User = new User;
		if ( ! $User->isSuperUser() ) {
			message( '没有操作权限', 'back', 'error' );
		}
		( new SiteUser() )->delOwner( SITEID );
		message( '删除站长成功', 'back', 'success' );
	}
}