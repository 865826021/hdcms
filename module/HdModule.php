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

	//构造函数
	public function __construct() {
		$this->config   = \Module::getModuleConfig();
		$this->template = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/system/template';
		define( '__TEMPLATE__', __ROOT__ . '/' . $this->template );
	}

	//保存模块配置
	public function saveConfig( $field ) {
		$id              = Db::table( 'module_setting' )->where( 'siteid', SITEID )->where( 'module', v( 'module.name' ) )->pluck( 'id' );
		$model           = $id ? ModuleSetting::find( $id ) : new ModuleSetting();
		$model['config'] = $field;
		$model->save();
	}
}