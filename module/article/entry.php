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

/**
 * 站点入口
 * Class entry
 * @package module\article
 */
class entry {
	//首页
	public function doWebHome() {
		$webid = q( 'get.webid', 0, 'intval' );
		$web   = Db::table( 'web' )->where( 'id', '=', $webid )->first();
		Session::set( 'webid', $webid );
		//模板风格
		$style = Db::table( 'template' )->where( 'tid', '=', $web['template_tid'] )->pluck( 'name' ) ?: 'default';
		$path  = 'theme/' . $style . '/' . ( IS_MOBILE ? 'mobile' : 'web' );
		echo $path;
		define( '__TEMPLATE__', $path );
		View::make( $path . '/index.html' );
	}

	//栏目页
	public function category() {
		$cid = q( 'get.cid', 0, 'intval' );
		$cat = Db::table( 'web_category' )->field( 'title cat_name,description cat_description,ishomepage' )->where( 'cid', '=', $cid )->first();
		//模板风格
		$style = Db::table( 'template' )->where( 'tid', '=', $cat['template_tid'] )->pluck( 'name' ) ?: 'default';
		$path  = "theme/{$style}/{$this->dir}";
		$file  = $cat['ishomepage'] ? 'article_index.html' : 'article_list.html';
		if ( is_file( $path . '/' . $file ) ) {
			$tpl = $path . '/' . $file;
			define( '__TEMPLATE__', $path );
		} else {
			//模板不存在时使用默认模板
			$tpl = 'theme/default/' . $this->dir . '/article/' . $file;
			define( '__TEMPLATE__', "theme/default/{$this->dir}/article" );
		}
		View::with( 'hdcms', $cat );
		View::make( $tpl );
	}

	//内容页
	public function content() {
		$aid     = q( 'get.aid', 0, 'intval' );
		$field_name
		         = 'c.siteid,c.cid,c.title catname,c.description cat_description,c.template_tid,c.css,c.linkurl cat_linkurl,c.icon cat_icon,a.aid,a.category_cid,a.iscommend
        ,a.ishot,a.title,a.description,a.source,a.author,a.orderby,a.linkurl,a.createtime,a.click,a.thumb,a.content';
		$article = Db::table( 'web_article a' )
		             ->join( 'web_category c', 'a.category_cid', '=', 'c.cid' )
		             ->where( 'aid', '=', $aid )
		             ->field( $field_name )
		             ->first();
		//模板风格
		$style = Db::table( 'template' )->where( 'tid', '=', $article['template_tid'] )->pluck( 'name' ) ?: 'default';
		$path  = "theme/{$style}/{$this->dir}";
		if ( is_file( $path . '/article.html' ) ) {
			$tpl = $path . '/article.html';
			define( '__TEMPLATE__', $path );
		} else {
			//模板不存在时使用默认模板
			$tpl = 'theme/default/' . $this->dir . '/article/article.html';
			define( '__TEMPLATE__', "theme/default/{$this->dir}/article" );
		}
		View::with( 'hdcms', $article );
		View::make( $tpl );
	}

	//图文消息回复
	public function news() {
		$id      = q( 'get.id', 0, 'intval' );
		$article = Db::table( 'reply_news' )->where( 'id', $id )->first();
		$tpl     = 'theme/default/' . $this->dir . '/article/article.html';
		define( '__TEMPLATE__', "theme/default/{$this->dir}/article" );
		View::with( 'hdcms', $article );
		View::make( $tpl );
	}
}