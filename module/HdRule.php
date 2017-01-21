<?php namespace module;

/**
 * 模块控制类 module.php继承
 * Class HdModule
 * @package module
 * @author 向军
 */
abstract class HdRule {
	//模板目录
	protected $template;
	//配置项
	protected $config;

	//构造函数
	public function __construct() {
		auth();
		$this->config   = \Module::getModuleConfig();
		$this->template = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/system/template';
		define( '__TEMPLATE__', __ROOT__ . '/' . $this->template );
	}
}