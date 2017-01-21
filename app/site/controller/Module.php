<?php namespace app\site\controller;
/**
 * 模块动作访问处理
 * Class Module
 * @package site\controller
 */
class Module {
	public function __construct() {
		auth();
	}

	//模块配置
	public function setting() {
		//后台分配菜单
		$class = '\addons\\' . v( 'module.name' ) . '\system\Config';
		if ( ! class_exists( $class ) || ! method_exists( $class, 'settingsDisplay' ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		View::with( 'module_action_name', '参数设置' );
		$obj     = new $class();
		$setting = \Module::getModuleConfig( v( 'module.name' ) );

		return $obj->settingsDisplay( $setting );
	}
}