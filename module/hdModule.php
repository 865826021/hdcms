<?php namespace module;

use system\model\ModuleSetting;

/**
 * 模块控制类 module.php继承
 * Class HdModule
 * @package module
 * @author 向军
 */
abstract class HdModule {
	//模板目录
	protected $template;
	//配置项
	protected $config;
	//模型
	protected $moduleSetting;

	//构造函数
	public function __construct() {
		$this->moduleSetting = new ModuleSetting();
		$this->template      = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/template';
		$this->config        = $this->moduleSetting->getModuleConfig();
		define( '__TEMPLATE__', $this->template );
	}

	//保存模块配置
	public function saveSettings( $field ) {
		$id              = $this->moduleSetting->where( 'siteid', SITEID )->where( 'module', v( 'module.name' ) )->pluck( 'id' );
		$data['siteid']  = SITEID;
		$data['module']  = v( 'module.name' );
		$data['status']  = 1;
		$data['setting'] = serialize( $field );
		if ( $id ) {
			Db::table( 'module_setting' )->where( 'id', '=', $id )->update( $data );
		} else {
			Db::table( 'module_setting' )->insert( $data );
		}
	}
}