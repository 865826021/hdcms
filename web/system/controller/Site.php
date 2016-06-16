<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\system\controller;

use system\model\MemberFields;
use system\model\MemberGroup;
use system\model\Modules;
use system\model\Package;
use system\model\SiteModules;
use system\model\SitePackage;
use system\model\SiteSetting;
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
	protected $db;
	protected $siteid;

	public function __construct() {
		//登录检测
		if ( ! m( 'User' )->isLogin() ) {
			message( '你还没有登录,无法进行操作', u( 'entry/login' ), 'warning' );
		}
		$this->siteid = Session::get( 'siteid' );
		$this->db     = new \system\model\Site();
	}

	//站点列表
	public function lists() {
		//加载当前操作的站点缓存
		$this->db->loadSite();
		$db = Db::table( 'site' )
		        ->field( 'site.siteid,site.name,user.starttime,endtime,site_wechat.icon,site_wechat.is_connect' )
		        ->leftJoin( 'site_user', 'site.siteid', '=', 'site_user.siteid' )
		        ->leftJoin( 'user', 'site_user.uid', '=', 'user.uid' )
		        ->leftJoin( 'site_wechat', 'site.siteid', '=', 'site_wechat.siteid' )
		        ->groupBy( 'site.siteid' );
		//按网站名称搜索
		if ( $sitename = q( 'post.sitename' ) ) {
			$db->where( 'site.name', 'like', "%{$sitename}%" );
		}
		//按网站域名搜索
		if ( $domain = q( 'post.domain' ) ) {
			$db->where( 'site.domain', 'like', "%{$domain}%" );
		}
		$userModel = new User();
		//普通站长获取站点列表
		if ( ! $userModel->isSuperUser() ) {
			$db->where( 'user.uid', '=', Session::get( 'user.uid' ) );
		}
		$sites        = $db->get();
		$packageModel = new Package();
		$userModel    = new User();
		foreach ( $sites as $k => $v ) {
			$v['package'] = $packageModel->getSiteAllPackageData( $v['siteid'] );
			$v['owner']   = $userModel->getSiteOwner( $v['siteid'] );
			if ( ! empty( $v['owner'] ) ) {
				$v['owner']['group_name'] = Db::table( 'user_group' )->where( 'id', $v['owner']['groupid'] )->pluck( 'name' );
			}

			$sites[ $k ] = $v;
		}
		View::with( 'sites', $sites );
		View::make();
	}

	//网站列表页面,获取站点包信息
	public function package() {
		//根据站长所在会员组获取套餐
		$packageModel = new Package();
		$pids         = $packageModel->getSiteAllPackageIds( $this->siteid );
		$package      = [ ];
		if ( in_array( - 1, $pids ) ) {
			$package[] = "所有服务";
		} else if ( ! empty( $pids ) ) {
			$package = Db::table( 'package' )->whereIn( 'id', $pids )->lists( 'name' );
		}
		//获取模块
		$modulesModel = new Modules();
		$modules      = $modulesModel->getSiteAllModules( $this->siteid );
		ajax( [ 'package' => $package, 'modules' => $modules ] );
	}

	//添加站点
	public function post() {
		switch ( $_GET['step'] ) {
			//设置站点
			case 'site_setting':
				if ( IS_POST ) {
					//添加站点信息
					if ( ! $siteid = $this->db->add( $_POST ) ) {
						message( $this->db->getError(), 'back', 'error' );
					}
					//添加站长数据,系统管理员不添加数据
					( new SiteUser() )->setSiteOwner( $siteid, Session::get( 'user.uid' ) );
					//初始站点配置
					( new SiteSetting() )->add( [ 'siteid' => $siteid ] );
					//添加默认会员组
					( new MemberGroup() )->add( [ 'siteid' => $siteid, 'isdefault' => 1, 'is_system' => 1 ] );
					//创建用户字段表数据
					( new MemberFields() )->InitializationSiteTableData( $siteid );
					//更新站点缓存
					$this->db->updateSiteCache( $siteid );
					go( u( 'post', [ 'step' => 'wechat', 'siteid' => $siteid ] ) );
				}
				View::make( 'site_setting' );
			//设置公众号
			case 'wechat':
				$UserPermissionModel = new UserPermission();
				//验证当前用户站点权限
				if ( ! $UserPermissionModel->isOwner( $this->siteid ) ) {
					message( '您不是网站管理员无法操作' );
				}
				//微信帐号管理
				if ( IS_POST ) {
					$SiteWechatModel = new SiteWechat();
					$_POST['siteid'] = $this->siteid;
					if ( ! $weid = $SiteWechatModel->add( $_POST ) ) {
						message( $SiteWechatModel->getError(), 'back', 'error' );
					}
					//设置站点微信记录编号
					$data = [ 'siteid' => $this->siteid, 'weid' => $weid ];
					if ( ! $this->db->save( $data ) ) {
						message( $this->db->getError(), 'back', 'error' );
					}
					//更新站点缓存
					$this->db->updateSiteCache( $this->siteid );
					go( u( 'post', [ 'step' => 'access_setting', 'siteid' => $this->siteid ] ) );
				}
				View::make( 'post_weixin' );
			//设置权限,只有系统管理员可以操作
			case 'access_setting':
				//非系统管理员直接跳转到第四步,只有系统管理员可以设置用户扩展套餐与模块
				if ( ! m( 'User' )->isSuperUser() ) {
					go( u( 'post', [ 'step' => 'explain', 'siteid' => $this->siteid ] ) );
				}
				if ( IS_POST ) {
					//站点允许使用的空间大小
					Db::table( 'site' )->where( 'siteid', $this->siteid )->update( [ 'allfilesize' => q( 'post.allfilesize', 200, 'intval' ) ] );
					//删除站点旧的套餐
					Db::table( 'site_package' )->where( 'siteid', $this->siteid )->delete();
					if ( $package_id = q( 'post.package_id', [ ] ) ) {
						foreach ( $package_id as $id ) {
							Db::table( 'site_package' )->insert( [ 'siteid' => $this->siteid, 'package_id' => $id, ] );
						}
					}
					//添加扩展模块
					Db::table( 'site_modules' )->where( 'siteid', $siteid )->delete();
					if ( $modules = q( 'post.modules', [ ] ) ) {
						foreach ( $modules as $mid ) {
							Db::table( 'site_modules' )->insert( [ 'siteid' => $this->siteid, 'module' => $mid, ] );
						}
					}
					//设置站长
					if ( $manage_id = q( 'post.uid', 0, 'intval' ) ) {
						//删除站点用户信息
						Db::table( 'site_user' )
						  ->where( 'siteid', $this->siteid )
						  ->where( 'role', 'owner' )
						  ->orWhere( 'uid', '=', $manage_id )
						  ->delete();
						//设置站点管理员
						Db::table( 'site_user' )->insert( [ 'siteid' => $this->siteid, 'uid' => $manage_id, 'role' => 'owner' ] );
					}
					$this->db->updateSiteCache( $this->siteid );
					if ( $from_url = q( 'get.from' ) ) {
						//有来源地址,比如从站点列表进入
						message( '站点信息修改成功', $from_url, 'success' );
					} else {
						go( u( 'post', [ 'step' => 'explain', 'siteid' => $siteid ] ) );
					}
				}
				//获取站长信息
				$user         = ( new SiteUser() )->getSiteOwner( $this->siteid );
				$packageModel = new Package();
				//获取系统所有套餐
				$systemAllPackages = $packageModel->getSystemAllPackageData();
				//扩展模块
				$extModule = ( new SiteModules() )->getSiteExtModules( $this->siteid );
				View::with( [
					'systemAllPackages' => $systemAllPackages,
					'extPackage'        => ( new SitePackage() )->getSiteExtPackageIds( $this->siteid ),
					'defaultPackage'    => ( new Package() )->getSiteDefaultPackageIds( $this->siteid ),
					'extModule'         => $extModule,
					'user'              => $user,
					'site'              => $this->db->find( $this->siteid )
				] );
				View::make( 'access_setting' );
			case 'explain':
				//验证当前用户站点权限
				$model = new UserPermission();
				if ( ! $model->isOwner( $this->siteid ) ) {
					message( '你没有管理该站点的权限' );
				}
				//更新站点缓存
				$this->db->updateSiteCache( $this->siteid );
				//引导页面
				$wechat = Db::table( 'site_wechat' )->where( 'siteid', $this->siteid )->first();
				View::with( 'wechat', $wechat )->make( 'explain' );
		}
	}

	//删除站点
	public function remove() {
		$model = new UserPermission();
		if ( $model->isOwner( $this->siteid ) ) {
			$this->db->remove( $this->siteid );
			message( '网站删除成功', 'back', 'success' );
		}
		message( '你不是站长不可以删除网站', 'back', 'error' );
	}

	//编辑站点
	public function edit() {
		$UserPermissionModel = new UserPermission();
		if ( ! $UserPermissionModel->isOwner( $this->siteid ) ) {
			message( '你没有编辑站点的权限', 'back', 'success' );
		}
		if ( IS_POST ) {
			$_POST['siteid'] = $this->siteid;
			if ( ! $this->db->save( $_POST ) ) {
				message( $this->db->getError(), 'back', 'error' );
			}
			//更新站点数据
			$this->db->save( $_POST );
			//更新微信数据
			Db::table( 'site_wechat' )->where( 'siteid', $this->siteid )->update( $_POST );
			//测试连接
			$wechat = Db::table( 'site_wechat' )->where( 'siteid', $this->siteid )->first();
			c( "weixin", $wechat );
			//与微信官网通信绑定验证
			$status = Weixin::getAccessToken();
			Db::table( 'site_wechat' )->where( 'siteid', $this->siteid )->update( [ 'is_connect' => $status ? 1 : 0 ] );
			$this->db->updateSiteCache( $this->siteid );
			if ( $status ) {
				message( '恭喜, 公众号连接成功', 'lists', 'success' );
			} else {
				message( '公众号连接失败,请查看配置项', 'back', 'error' );
			}
		}
		$site   = Db::table( 'site' )->where( 'siteid', $this->siteid )->first();
		$wechat = Db::table( 'site_wechat' )->where( 'siteid', $this->siteid )->first();
		View::with( [ 'site' => $site, 'wechat' => $wechat ] )->make();
	}

	//公众号连接测试
	public function connect() {
		//与微信官网通信绑定验证
		$status = Weixin::getAccessToken();
		Db::table( 'site_wechat' )->where( 'siteid', $this->siteid )->update( [ 'is_connect' => $status ? 1 : 0 ] );
		if ( $status ) {
			ajax( [ 'valid' => TRUE, 'message' => '恭喜, 微信公众号接入成功' ] );
		} else {
			ajax( [ 'valid' => FALSE, 'message' => '公众号接入失败' ] );
		}
	}

}