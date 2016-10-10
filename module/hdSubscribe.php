<?php namespace module;

use system\model\ModuleSetting;

/**
 * 模块订阅消息
 * Class hdSubscribe
 * @package system\core
 * @author 向军
 */
abstract class hdSubscribe {
	//配置项
	protected $config;

	public function __construct() {
		$this->config = ( new ModuleSetting() )->getModuleConfig();
	}

	abstract function handle();

	public function __call( $method, $arguments ) {
		$instance = \Weixin::instance( 'message' );
		if ( method_exists( $instance, $method ) ) {
			call_user_func_array( [ $instance, $method ], $arguments );
		}
	}
}