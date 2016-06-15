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

/**
 * 会员中心设置
 * Class ucenter
 * @package module\article
 */
class ucenter extends hdSite {
	public function doSitePost() {
		if ( IS_POST ) {
			$modules = json_decode( $_POST['modules'], TRUE );
			//会员中心数据
			if ( empty( $modules[0]['params']['thumb'] ) ) {
				message( '封面图片不能为空', 'back', 'error' );
			}

			$data['siteid']      = v( 'site.siteid' );
			$data['title']       = $modules[0]['params']['title'];
			$data['description'] = $modules[0]['params']['description'];
			$data['params']      = $_POST['modules'];
			$data['html']        = $_POST['html'];
			$data['web_id']      = 0;
			$data['type']        = 3;
			$data['status']      = 1;
			$data['createtime']  = time();
			if ( Db::table( 'web_page' )->where( 'siteid', '=', $data['siteid'] )->where( 'type', '=', 3 )->get() ) {
				Db::table( 'web_page' )->where( 'siteid', '=', $data['siteid'] )->where( 'type', '=', 3 )->update( $data );
			} else {
				Db::table( 'web_page' )->insert( $data );
			}
			//添加菜单,首先删除原菜单
			$menus = json_decode( $_POST['menus'], TRUE );
			//删除旧的菜单
			Db::table( 'web_nav' )->where( 'siteid', v( 'site.siteid' ) )->where( 'entry', 'profile' )->delete();
			foreach ( $menus as $m ) {
				if ( ! empty( $m['name'] ) ) {
					$data             = [ ];
					$data['name']     = $m['name'];
					$data['css']      = $m['css'];
					$data['url']      = $m['url'];
					$data['icontype'] = 1;
					$data['entry']    = 'profile';
					api( 'article' )->addNav( $data );
				}
			}
			//************************************回复关键词处理************************************
			$rid = Db::table( 'rule' )->where( 'siteid', v( 'site.siteid' ) )->where( 'name', '会员中心' )->pluck( 'rid' );
			if ( $rid ) {
				Db::table( 'rule' )->where( 'rid', $rid )->delete();
				Db::table( 'rule_keyword' )->where( 'rid', $rid )->delete();
				Db::table( 'reply_cover' )->where( 'rid', $rid )->delete();
			}
			$ucenter              = $modules[0]['params'];
			$rule['name']         = '会员中心';
			$rule['module']       = 'cover';
			$rule['keyword']      = [
				[
					'content' => $ucenter['keyword'],
					'type'    => 1,
				]
			];
			$rid                  = api( 'rule' )->save( $rule );
			$cover['siteid']      = v( 'site.siteid' );
			$cover['web_id']      = 0;
			$cover['rid']         = $rid;
			$cover['module']      = '';
			$cover['do']          = '';
			$cover['title']       = $ucenter['title'];
			$cover['description'] = $ucenter['description'];
			$cover['thumb']       = $ucenter['thumb'];
			$cover['url']         = "?a=uc/entry/home&siteid=" . v( 'site.siteid' );
			Db::table( 'reply_cover' )->insert( $cover );
			message( '会员中心视图保存成功', 'refresh', 'success' );
		}
		//模块
		$modules = Db::table( 'web_page' )->where( 'siteid', '=', v( 'site.siteid' ) )->where( 'type', '=', 3 )->pluck( 'params' );
		//菜单
		$menusData = Db::table( 'web_nav' )
		               ->where( 'siteid', '=', v( 'site.siteid' ) )
		               ->where( 'entry', '=', 'profile' )
		               ->field( 'id,name,url,css' )
		               ->orderBy( 'orderby', 'desc' )
		               ->orderBy( 'id', 'asc' )
		               ->get();
		//将CSS样式返序列化,用于显示图标等信息
		foreach ( $menusData as $k => $v ) {
			$menusData[ $k ]['css'] = json_decode( $v['css'] ,true);
		}
		View::with( [ 'modules' => $modules, 'menusData' => $menusData ] );
		View::make( $this->template . '/ucenter/post.php' );
	}
}