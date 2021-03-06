<?php namespace module\article\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
use module\HdController;

/**
 * 官网模板管理
 * Class Template
 * @package module\article\controller
 */
class Template extends HdController {
	//选择模板
	public function lists() {
		$data = \Template::getSiteAllTemplate( SITEID, Request::get( 'type' ) );

		return view( $this->template . '/template/template_lists.html' )->with( [ 'data' => $data ] );
	}

	/**
	 * 当前风格中的模板文件
	 * 用于为栏目或文章设置个性模板
	 */
	public function files() {
		$template = \Template::getTemplateData();
		$dir      = "theme/{$template['name']}";
		View::with( [ 'web' => glob( $dir . '/web/*.html' ), 'mobile' => glob( $dir . '/mobile/*.html' ) ] );

		return view( $this->template . '/template/template_files.html' );
	}
}