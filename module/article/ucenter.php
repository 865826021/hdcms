<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\article;

use module\hdSite;
use system\model\ReplyCover;
use system\model\Rule;
use system\model\RuleKeyword;
use system\model\WebNav;
use system\model\WebPage;

/**
 * 会员中心设置
 * Class ucenter
 * @package module\article
 */
class ucenter extends hdSite {
	protected $webPage;

	public function __construct() {
		parent::__construct();
		$this->webPage = new WebPage();
	}

	public function doSitePost() {
		if ( IS_POST ) {
			$modules = json_decode( $_POST['modules'], TRUE );
			//会员中心数据
			if ( empty( $modules[0]['params']['thumb'] ) ) {
				message( '封面图片不能为空', 'back', 'error' );
			}
			$data['title']       = $modules[0]['params']['title'];
			$data['description'] = $modules[0]['params']['description'];
			$data['params']      = $_POST['modules'];
			$data['html']        = $_POST['html'];
			$data['type']        = 3;
			$data['status']      = 1;
			$res                 = $this->webPage->where( 'siteid', SITEID )->where( 'type', 3 )->first();
			$action              = $res ? 'save' : 'add';
			$data['id']          = $res['id'];
			if ( ! $this->webPage->$action( $data ) ) {
				message( $this->webPage->getError(), 'back', 'error' );
			}
			//添加菜单,首先删除原菜单
			$menus = json_decode( $_POST['menus'], TRUE );
			//删除旧的菜单
			$webNavModel = new WebNav();
			$webNavModel->where( 'siteid', SITEID )->where( 'entry', 'profile' )->delete();
			foreach ( $menus as $m ) {
				if ( ! empty( $m['name'] ) ) {
					$data             = [ ];
					$data['name']     = $m['name'];
					$data['css']      = $m['css'];
					$data['url']      = $m['url'];
					$data['icontype'] = 1;
					$data['entry']    = 'profile';
					if ( ! $webNavModel->add( $data ) ) {
						message( $webNavModel->getError(), 'back', 'error' );
					}
				}
			}
			//************************************回复关键词处理************************************
			$rid    = Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'name', '会员中心' )->pluck( 'rid' );
			$action = $rid ? 'save' : 'add';
			//会员中心顶部资料,回复关键词,描述,缩略图
			$ucenter = $modules[0]['params'];
			//添加回复规则
			$rule['rid']    = $rid;
			$rule['name']   = '会员中心';
			$rule['module'] = 'cover';
			$ruleModel      = new Rule();
			if ( ! $insertRid = $ruleModel->$action( $rule ) ) {
				message( $ruleModel->getError(), 'back', 'error' );
			}
			$rid = $action == 'add' ? $insertRid : $rid;
			//回复关键词
			$ruleKeyword        = new RuleKeyword();
			$keyword['content'] = $ucenter['keyword'];
			$keyword['module']  = 'cover';
			$keyword['rid']     = $rid;
			if ( $res = $ruleKeyword->where( 'rid', $rid )->first() ) {
				$keyword['id'] = $res['id'];
				$action        = 'save';
			} else {
				$action = 'add';
			}
			if ( ! $ruleKeyword->$action( $keyword ) ) {
				message( $ruleKeyword->getError(), 'back', 'error' );
			}
			//回复封面
			$replyCover           = new ReplyCover();
			$cover['rid']         = $rid;
			$cover['title']       = $ucenter['title'];
			$cover['description'] = $ucenter['description'];
			$cover['thumb']       = $ucenter['thumb'];
			$cover['url']         = "?a=uc/entry/home&t=web&siteid=" . SITEID;
			if ( $res = $replyCover->where( 'rid', $rid )->first() ) {
				$cover['id'] = $res['id'];
				$action      = 'save';
			} else {
				$action = 'add';
			}
			if ( ! $replyCover->$action( $cover ) ) {
				message( $replyCover->getError(), 'back', 'error' );
			}
			message( '会员中心视图保存成功', 'refresh', 'success' );
		}
		//模块
		$modules = $this->webPage->where( 'siteid', SITEID )->where( 'type', '=', 3 )->pluck( 'params' );
		//菜单
		$menusData
			= Db::table( 'web_nav' )
			    ->where( 'siteid', SITEID )
			    ->where( 'entry', 'profile' )
			    ->field( 'id,name,url,css' )
			    ->orderBy( 'orderby', 'desc' )
			    ->orderBy( 'id', 'asc' )
			    ->get();
		//将CSS样式返序列化,用于显示图标等信息
		foreach ( $menusData as $k => $v ) {
			$menusData[ $k ]['css'] = json_decode( $v['css'], TRUE );
		}

		View::with( [ 'modules' => $modules, 'menusData' => $menusData ] );
		View::make( $this->template . '/ucenter/post.php' );
	}
}