<?php namespace module\ucenter\controller;

use module\HdController;
use system\model\WebPage;

/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

/**
 * 移动端界面管理
 * Class Mobile
 * @package module\ucenter\controller
 */
class Mobile extends HdController {
	/**
	 * 移动端界面设置
	 */
	public function post() {
		$webPage = new WebPage();
		if ( IS_POST ) {
			$modules = json_decode( $_POST['modules'], TRUE );
			//会员中心数据
			if ( empty( $modules[0]['params']['thumb'] ) ) {
				message( '封面图片不能为空', 'back', 'error' );
			}
			$data['title']       = $modules[0]['params']['title'];
			$data['description'] = $modules[0]['params']['description'];
			$data['params']      = $modules;
			$data['html']        = $_POST['html'];
			$data['type']        = 3;
			$data['status']      = 1;
			$res                 = $webPage->where( 'siteid', SITEID )->where( 'type', 3 )->first() ?: [ ];
			$data['id']          = empty( $res['id'] ) ? 0 : $res['id'];
			$webPage->save( $data );
			//添加菜单,首先删除原菜单
			$menus = json_decode( $_POST['menus'], TRUE );
			//删除旧的菜单
			$webNavModel = new WebNav();
			$webNavModel->where( 'siteid', SITEID )->where( 'entry', 'profile' )->delete();
			foreach ( (array) $menus as $m ) {
				if ( ! empty( $m['name'] ) ) {
					$data             = [ ];
					$data['name']     = $m['name'];
					$data['css']      = $m['css'];
					$data['url']      = $m['url'];
					$data['icontype'] = 1;
					$data['entry']    = 'profile';
					$webNavModel->save( $data );
				}
			}
			//************************************回复关键词处理************************************
			$rid = Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'name', '##会员中心##' )->pluck( 'rid' );
			//会员中心顶部资料,回复关键词,描述,缩略图
			$ucenter = $modules[0]['params'];
			//添加回复规则
			$rule['rid']    = $rid;
			$rule['name']   = '##会员中心##';
			$rule['module'] = 'cover';
			//回复关键词
			$rule['keywords'] = [ [ 'content' => $ucenter['keyword'] ] ];
			service( 'WeChat' )->rule( $rule );
			//回复封面
			$replyCover           = new ReplyCover();
			$cover['rid']         = $rid;
			$cover['title']       = $ucenter['title'];
			$cover['description'] = $ucenter['description'];
			$cover['thumb']       = $ucenter['thumb'];
			$cover['url']         = "?a=entry/home&t=web&m=uc&siteid=" . SITEID;
			$replyCover->where( 'rid', $rid )->delete();
			$replyCover->save( $cover );
			message( '会员中心视图保存成功', 'refresh', 'success' );
		}
		//模块
		$modules = $webPage->where( 'siteid', SITEID )->where( 'type', 3 )->pluck( 'params' );
		//菜单
		$menusData = Db::table( 'navigate' )
		               ->where( 'siteid', SITEID )
		               ->where( 'entry', 'profile' )
		               ->field( 'id,name,url,css' )
		               ->orderBy( 'orderby', 'desc' )
		               ->orderBy( 'id', 'asc' )
		               ->get() ?: [ ];
		//将CSS样式返序列化,用于显示图标等信息
		foreach ( $menusData as $k => $v ) {
			$menusData[ $k ]['css'] = json_decode( $v['css'], TRUE );
		}
		View::with( 'rid', Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'name', '##会员中心##' )->pluck( 'rid' ) );
		View::with( [ 'modules' => $modules, 'menusData' => $menusData ] );

		return view( $this->template . '/mobile_post.html' );
	}
}