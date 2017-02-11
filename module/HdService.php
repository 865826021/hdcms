<?php namespace module;

/**
 * 服务业务基类
 * Class HdService
 * @package module
 */
abstract class HdService {
	//站点编号
	protected $siteid;
	//模板目录
	protected $template;
	//配置项
	protected $config;

	public function __construct() {
		$this->siteid   = SITEID;
		$this->config   = \Module::getModuleConfig();
		$this->template = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/service/template';
		template_path( $this->template );
		template_url( __ROOT__ . '/' . $this->template );
	}
}