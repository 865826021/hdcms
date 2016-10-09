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
		$this->webid = isset( $_GET['webid'] ) ? intval( $_GET['webid'] ) : Session::get( 'webid' );
		if ( ! $this->webid ) {
			$web         = service( 'web' )->getDefaultWeb();
			$this->webid = $web['id'];
		}
		Session::set( 'webid', $this->webid );
	}

	//首页
	public function doWebHome() {
		$web = Db::table( 'web' )->where( 'id', $this->webid )->first();
		//模板风格
		if ( empty( $web['template_name'] ) ) {
			$web['template_name'] = Db::table( 'template' )->where( 'is_default', 1 )->pluck( 'name' );
		}
		$path = 'theme/' . $web['template_name'] . '/' . $this->dir;
		define( '__TEMPLATE__', $path );
		View::with( 'hdcms', $web );

		return View::make( $path . '/index.html' );
	}

	//栏目页
	public function doWebCategory() {
		$cid = q( 'get.cid', 0, 'intval' );
		$cat = Db::table( 'web_category' )->where( 'siteid', SITEID )->where( 'cid', $cid )->first();
		//模板风格编号,栏目没有选择时使用站点模块
		if ( empty( $cat['template_name'] ) ) {
			$cat['template_name'] = Db::table( 'web' )->where( 'id', $this->webid )->pluck( 'template_name' );
			if ( empty( $cat['template_name'] ) ) {
				$cat['template_name'] = Db::table( 'template' )->where( 'is_default', 1 )->pluck( 'name' );
			}
		}
		$path = "theme/{$cat['template_name']}/{$this->dir}";
		$file = $cat['ishomepage'] ? 'article_index.html' : 'article_list.html';
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
		if ( empty( $article ) ) {
			message( '文章不存在', 'back', 'error' );
		}
		$article['url'] = web_url( 'entry/content', [ 'aid' => $article['aid'], 'cid' => $article['category_cid'] ], 'article' );
		//栏目
		$category        = Db::table( 'web_category' )->where( 'cid', $article['category_cid'] )->first();
		$category['url'] = empty( $category['cat_linkurl'] ) ? web_url( 'entry/category', [ 'cid' => $category['cid'] ], 'article' ) : $category['cat_linkurl'];
		//模板风格
		$template_name = $article['template_name'] ?: $category['template_name'];
		if ( empty( $template_name ) ) {
			$template_name = Db::table( 'web' )->where( 'id', $this->webid )->pluck( 'template_name' );
			if ( empty( $template_name ) ) {
				$template_name = Db::table( 'template' )->where( 'is_default', 1 )->pluck( 'name' );
			}
		}
		$path = "theme/{$template_name}/{$this->dir}";
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

		return View::make( $tpl );
	}
}