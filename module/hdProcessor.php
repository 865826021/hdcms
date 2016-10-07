<?php namespace module;

/**
 * 模块处理消息
 * Class hdProcessor
 * @package system\core
 * @author 向军
 */
abstract class hdProcessor {
	//配置项
	protected $config;

	public function __construct() {
		$this->config = service( 'module' )->getModuleConfig();
	}

	//回复方法
	abstract function handle( $rid );

	public function __call( $method, $arguments ) {
		$instance = \Weixin::instance( 'message' );
		if ( method_exists( $instance, $method ) ) {
			call_user_func_array( [ $instance, $method ], $arguments );
		}
	}
}