<?php namespace addons\store\controller;

use module\HdController;

/**
 * 模块主页
 * Class Entry
 * @package addons\store\controller
 */
class Entry extends HdController {
	//主页
	public function home() {
		$type = $_GET['type'] = Request::get( 'type', 'module' );
		//这个操作被定义用来呈现 桌面入口导航
		$type_title = $type == 'module' ? '功能模块' : '风格模板';
		if ( $type == 'template' ) {
			//模板列表
			$category = \Template::getTitleLists();
		} else {
			//模块列表
			$category = \Module::getModuleTitles();
		}
		View::with( 'category', $category );
		View::with( 'type_title', $type_title );
		return view( $this->template . '/entry/home.html' );
	}
}