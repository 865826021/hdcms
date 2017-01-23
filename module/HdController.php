<?php namespace module;

/**
 * 模块业务基类
 * Class hdSite
 * @package system\core
 * @author 向军
 */
abstract class HdController {
	//站点编号
	protected $siteid;
	//模板目录
	protected $template;
	//配置项
	protected $config;

	public function __construct() {
		$this->siteid   = SITEID;
		$this->config   = \Module::getModuleConfig();
		$this->template = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/template';
		template_path($this->template);
		template_url(__ROOT__ . '/' . $this->template);
	}
}