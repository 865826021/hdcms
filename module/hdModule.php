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
	protected $view;
	//配置项
	protected $config;

	//构造函数
	public function __construct() {
		$this->config = \Module::getModuleConfig();
		$this->view   = ( v( 'module.is_system' ) ? "module/" : "addons/" ) . v( 'module.name' ) . '/view';
		define( '__TEMPLATE__', __ROOT__ . '/' . $this->view );
	}

	//保存模块配置
	public function saveSettings( $field ) {
		$model = ModuleSetting::where( 'siteid', SITEID )->where( 'module', v( 'module.name' ) )->find();
		if ( empty( $model ) ) {
			$model = new ModuleSetting();
		}
		$model['siteid']  = SITEID;
		$model['module']  = v( 'module.name' );
		$model['status']  = 1;
		$model['setting'] = $field;
		$model->save();
	}
}