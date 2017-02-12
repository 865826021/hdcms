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
		template_url( __ROOT__ . '/' . $this->template );
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

//		$db = new WebContent();
//		$db->where('category_cid',Request::get('cid'))->where('siteid',SITEID);
//        p(Request::get());
//        $_data = $db->paginate(3);exit;

		if ( IS_MOBILE ) {
			return view( $this->template . '/article_list.html' );
		} else {

		}
	}

	/**
	 * 文章内容页访问入口
	 */
	public function content() {
		echo 1221;
	}
}