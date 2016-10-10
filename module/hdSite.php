<?php namespace module;

use system\model\ModuleSetting;

/**
 * 模块业务基类
 * Class hdSite
 * @package system\core
 * @author 向军
 */
abstract class hdSite {

	//站点编号
	protected $siteid;

	//模板目录
	protected $template;

	//配置项
	protected $config;

	//构造函数
	public function __construct() {
		$this->siteid           = SITEID;
		$this->template         = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/template';
		$this->ucenter_template = 'ucenter/' . ( IS_MOBILE ? "mobile" : "web" );
		$this->config           = service( 'module' )->getModuleConfig();
		defined( '__TEMPLATE_PATH__' ) or define( '__TEMPLATE_PATH__', $this->template );
		defined( '__TEMPLATE__' ) or define( '__TEMPLATE__', __ROOT__ . '/' . $this->template );
		defined( '__UCENTER_TEMPLATE__' ) or define( '__UCENTER_TEMPLATE__', __ROOT__ . '/' . $this->ucenter_template );

	}

}