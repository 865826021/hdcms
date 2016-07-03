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

use system\model\Web;

/**
 * 站点入口
 * Class entry
 * @package module\article
 */
class entry {

	//模板目录
	protected $db;

	//目录类型 mobile手机 web桌面
	protected $dir;

	protected $webid;

	public function __construct() {
		$this->dir   = IS_MOBILE ? 'mobile' : 'web';
		$this->webid = q( 'get.webid', Session::get( 'webid' ), 'intval' );
		if ( ! $this->webid ) {
			$web         = ( new Web() )->getDefaultWeb();
			$this->webid = $web['id'];
		}
		Session::set( 'webid', $this->webid );
	}

	//首页
	public function doWebHome() {
		$web = Db::table( 'web' )->where( 'id', $this->webid )->first();
		//模板风格
		$style = Db::table( 'template' )->where( 'tid', $web['template_tid'] )->pluck( 'name' ) ?: 'default';
		$path  = 'theme/' . $style . '/' . $this->dir;
		define( '__TEMPLATE__', $path );
		View::with( 'hdcms', $web );
		View::make( $path . '/index.html' );
	}

	//栏目页
	public function doWebCategory() {
		$cid = q( 'get.cid', 0, 'intval' );
		$cat = Db::table( 'web_category' )
		         ->field( 'title cat_name,description cat_description,ishomepage' )
		         ->where( 'siteid', SITEID )
		         ->where( 'cid', '=', $cid )
		         ->first();
		//模板风格编号,栏目没有选择时使用站点模块
		if ( empty( $cat['template_tid'] ) ) {
			$template_id = Db::table( 'web' )->where( 'id', $this->webid )->pluck( 'template_tid' );
		} else {
			$template_id = $cat['template_tid'];
		}
		$style = Db::table( 'template' )->where( 'tid', $template_id )->pluck( 'name' );
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
	public function doWebContent() {
		$aid = q( 'get.aid', 0, 'intval' );
		//文章
		$article = Db::table( 'web_article' )->where( 'siteid', SITEID )->where( 'aid', $aid )->first();
		//栏目
		$category = Db::table( 'web_category' )->where( 'cid', $article['category_cid'] )->first();
		//模板风格
		$templateTid = $article['template_tid'] ?: $category['template_tid'];
		if ( empty( $templateTid ) ) {
			$templateTid = Db::table( 'web' )->where( 'id', $this->webid )->pluck( 'template_tid' );
		}
		$style = Db::table( 'template' )->where( 'tid', $templateTid )->pluck( 'name' ) ?: 'default';
		$path  = "theme/{$style}/{$this->dir}";
		if ( is_file( $path . '/article.html' ) ) {
			$tpl = $path . '/article.html';
			define( '__TEMPLATE__', $path );
		} else {
			//模板不存在时使用默认模板
			$tpl = 'theme/default/' . $this->dir . '/article.html';
			define( '__TEMPLATE__', "theme/default/{$this->dir}" );
		}
		View::with( 'hdcms', $article );
		View::with( 'category', $category );
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