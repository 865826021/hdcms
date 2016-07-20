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
		if ( $res = $db->field( 'rule.rid,rule.name,rule.module' )->first() ) {
			$module = Db::table( 'modules' )->where( 'name', $res['module'] )->first();
			switch ( $res['module'] ) {
				case 'basic':
					$message = "该关键字已存在于 <a href='?s=site/reply/post&m=basic&rid=" . $res['rid'] . "&m=basic'>" . "文本消息 \"" . $res['name'] . "\"</a> 规则中";
					break;
				case 'news':
					$message = "该关键字已存在于 <a href='?s=site/reply/post&m=news&rid=" . $res['rid'] . "&m=basic'>" . "图文消息 \"" . $res['name'] . "\"</a> 规则中";
					break;
				case 'cover':
					$message = "该关键字已存在于 <a href='?s=site/reply/post&m=news&rid=" . $res['rid'] . "&m=basic'>" . "封面消息 \"" . $res['name'] . "\"</a> 规则中";
					break;
				default:
					$message = "该关键字已存在于 <a href='javascript:;'>" . $module['title']." ".$res['name'] . "</a> 规则中";
			}
		}
		if ( $res ) {
			ajax( [ 'valid' => 0, 'message' => $message ] );
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
			             ->where( 'siteid', SITEID )
			             ->where( 'status', 1 )
			             ->get();
		} else {
			$content = Db::table( 'rule_keyword' )->where( 'siteid', v( 'site.siteid' ) )->where( 'status', 1 )->limit( 10 )->get();
		}
		ajax( $content );
	}
}