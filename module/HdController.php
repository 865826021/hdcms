<?php namespace module;

/**
 * 模块业务基类
 * Class hdSite
 * @package system\core
 * @author 向军
 */
abstract class HdController {
	//模板目录
	protected $template;
	//配置项
	protected $config;
	public function __construct() {
		$this->config   = \Module::getModuleConfig();
		$this->template = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/template';
		define( 'TEMPLATE_PATH', $this->template );
		define( '__TEMPLATE__', __ROOT__ . '/' . $this->template );
	}
}