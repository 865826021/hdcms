<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace web\site\controller;

/**
 * 回复关键词处理
 * Class keyword
 * @package site\controller
 */
class Keyword {
	//检测微信关键词是否已经使用
	public function checkWxKeyword() {
		$db = Db::table( 'rule_keyword' )
		        ->join( 'rule', 'rule.rid', '=', 'rule_keyword.rid' )
		        ->where( 'rule_keyword.siteid', v( 'site.siteid' ) )
		        ->where( 'rule_keyword.content', q( 'post.content' ) );
		if ( $rid = q( 'post.rid' ) ) {
			//编辑时当前规则拥有的词不检测
			$db->where( 'rule.rid', '<>', $rid );
		}
		$res = $db->field( 'rule.rid,rule.name,rule.module' )->first();
		if ( $res ) {
			ajax( [ 'valid' => 0, 'message' => $res ] );
		} else {
			ajax( [ 'valid' => 1, 'message' => '关键词可以使用' ] );
		}
	}

	//获取关键词
	public function getKeywords() {
		$key = q( 'post.key' );
		if ( $key ) {
			$content = Db::table( 'rule_keyword' )
			             ->where( "content LIKE '%$key%'" )
			             ->where( 'siteid', v( 'site.siteid' ) )
			             ->where( 'status', 1 )
			             ->limit( 10 )
			             ->get();
		} else {
			$content = Db::table( 'rule_keyword' )->where( 'siteid', v( 'site.siteid' ) )->where( 'status', 1 )->limit( 10 )->get();
		}
		ajax( $content );
	}
}