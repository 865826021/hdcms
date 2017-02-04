<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace addons\store\controller;

use module\HdController;

/**
 * 前台模块管理
 * Class App
 * @package addons\store\controller
 */
class App extends HdController {
	public function __construct() {
		parent::__construct();
		\Member::isLogin();
		$this->assignAppTitle();
	}

	//分配标题名称
	protected function assignAppTitle() {
		$type   = Request::get( 'type' );
		$titles = [ 'module' => '模块', 'template' => '模板' ];
		View::with( [ 'app_title' => $titles[ $type ] ] );
	}

	//模块或插件列表
	public function lists() {
		return view( $this->template . '/app.lists.html' );
	}

	//添加插件或模块
	public function add() {
		return view( $this->template . '/app.add.html' );
	}
}