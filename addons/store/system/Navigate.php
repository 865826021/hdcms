<?php namespace addons\store\system;

/**
 * 模块导航菜单处理
 *
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\HdNavigate;

class Navigate extends HdNavigate {

	//插件模块
	public function module() {
		return controller_action( 'store.module.lists');
	}

	//风格模板
	public function template() {
		return controller_action( 'store.template.lists');
	}
}