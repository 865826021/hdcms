<?php namespace module;

use system\model\ModuleSetting;

/**
 * 模块业务基类
 * Class hdSite
 * @package system\core
 * @author 向军
 */
abstract class hdSite {
	//模板目录
	protected $template;

	//配置项
	protected $config;

	//构造函数
	public function __construct() {
		$this->template = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/template';
		$this->config   = ( new ModuleSetting() )->getModuleConfig();
		define( '__TEMPLATE_PATH__', $this->template );
		define( '__TEMPLATE__', __ROOT__ . '/' . $this->template );
	}

}