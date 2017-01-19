<?php namespace module\ucenter\controller;

use module\HdController;
use system\model\Navigate;
use system\model\Page;
use system\model\ReplyCover;

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
		$model = new Page();
		if ( IS_POST ) {
			//模块数据
			$modules = json_decode( $_POST['modules'], true );
			//查找旧的文章数据如果有时为编辑动作
			$res                  = Page::where( 'siteid', SITEID )->where( 'type', 'profile' )->first();
			$model                = $res['id'] ? Page::find( $res['id'] ) : new Page();
			$model['title']       = $modules[0]['params']['title'];
			$model['description'] = $modules[0]['params']['description'];
			$model['params']      = $modules;
			$model['html']        = $_POST['html'];
			$model['type']        = 'profile';
			$model['status']      = 1;
			$model->save();
			/**
			 * 添加菜单
			 * 首先删除原菜单然后添加新菜单
			 */
			$menus = json_decode( $_POST['menus'], true );
			Navigate::where( 'siteid', SITEID )->where( 'entry', 'profile' )->delete();
			foreach ( (array) $menus as $m ) {
				$NavigateModel = new Navigate();
				if ( ! empty( $m['name'] ) ) {
					$data['name']     = $m['name'];
					$data['css']      = $m['css'];
					$data['url']      = $m['url'];
					$data['icontype'] = 1;
					$data['entry']    = 'profile';
					$NavigateModel->save( $data );
				}
			}
			//************************************回复关键词处理************************************
			$rid = Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'name', '##移动端会员中心##' )->pluck( 'rid' );
			//会员中心顶部资料,回复关键词,描述,缩略图
			$ucenter = $modules[0]['params'];
			//添加回复规则
			$rule['rid']    = $rid;
			$rule['name']   = '##移动端会员中心##';
			$rule['module'] = 'cover';
			//回复关键词
			$rule['keywords'] = [ [ 'content' => $ucenter['keyword'] ] ];
			\Wx::rule( $rule );
			//回复封面
			$res                            = ReplyCover::where( 'siteid', SITEID )->where( 'module', 'ucenter' )->first();
			$replyCoverModel                = empty( $res['id'] ) ? new ReplyCover() : ReplyCover::find( $res['id'] );
			$replyCoverModel['rid']         = $rid;
			$replyCoverModel['title']       = $ucenter['title'];
			$replyCoverModel['description'] = $ucenter['description'];
			$replyCoverModel['thumb']       = $ucenter['thumb'];
			$replyCoverModel['module']      = 'ucenter';
			$replyCoverModel['url']         = "?m=ucenter&action=entry/home&siteid=" . SITEID;
			$replyCoverModel->save();
			message( '会员中心视图保存成功', 'refresh', 'success' );
		}
		//模块
		$modules = Page::where( 'siteid', SITEID )->where( 'type', 'profile' )->pluck( 'params' );
		if ( empty( $modules ) ) {
			$modules = '[{"id":"UCheader","name":"会员主页","params":{"title":"会员中心","bgImage":"","description":"","thumb":"","keyword":""},"issystem":1,"index":0,"displayorder":0}]';
		}
		//菜单
		$menus = Db::table( 'navigate' )
		           ->where( 'siteid', SITEID )
		           ->where( 'entry', 'profile' )
		           ->field( 'id,name,url,css' )
		           ->orderBy( 'orderby', 'desc' )
		           ->orderBy( 'id', 'asc' )
		           ->get();
		//将CSS样式返序列化,用于显示图标等信息
		foreach ( $menus as $k => $v ) {
			$menus[ $k ]['css'] = json_decode( $v['css'], true );
		}
		View::with( 'rid', Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'name', '##移动端会员中心##' )->pluck( 'rid' ) ?: 0 );
		View::with( [ 'modules' => $modules, 'menus' => json_encode( $menus ) ] );

		return view( $this->template . '/mobile_post.html' );
	}
}