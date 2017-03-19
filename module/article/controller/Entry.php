<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace module\article\controller;

use module\article\model\WebCategory;
use module\article\model\WebContent;
use module\HdController;

/**
 * 前台入口处理
 * Class Entry
 * @package module\article\controller
 */
class Entry extends HdController {
	public function __construct() {
		parent::__construct();
		//获取默认官网
		$template       = \Template::getTemplateData();
		$this->template = "theme/{$template['name']}/" . ( IS_MOBILE ? 'mobile' : 'web' );
		template_path( $this->template );
		define( '__ARTICLE_STYLE_PATH__', $this->template );
		template_url( __ROOT__ . '/' . $this->template );
		View::with( 'module.site', json_decode( Db::table( 'web' )->pluck( 'site_info' ), true ) );

	}

	/**
	 * 站点首页访问
	 * @return mixed
	 */
	public function index() {
		return view( $this->template . '/index.html' );
	}

	/**
	 * 栏目访问入口
	 */
	public function category() {
		$category = WebCategory::find( Request::get( 'cid' ) );
		//设置模型编号
		Request::set( 'get.mid', $category['mid'] );
		View::with( 'hdcms', $category->toArray() );
		$tpl = $category['ishomepage'] ? $category['index_tpl'] : $category['category_tpl'];

		return view( $this->template . "/{$tpl}" );
	}

	/**
	 * 文章内容页访问入口
	 */
	public function content() {
		$model = WebContent::find( Request::get( 'aid' ) );
		if ( empty( $model ) ) {
			message( '你访问的文章不存在', '', 'error' );
		}
		$hdcms    = $model->toArray();
		$category = WebCategory::find( $hdcms['cid'] )->toArray();
		//设置栏目链接
		$category['url'] = Link::get( $category['html_category'], $category );
		$category['url'] = str_replace( '{page}', Request::get( 'page', 1 ), $category['url'] );

		$hdcms['category'] = $category;
		View::with( [ 'hdcms' => $hdcms ] );
		$tpl = $hdcms['template'] ?: $category['content_tpl'];
		return view( $this->template . '/' . $tpl );
	}
}