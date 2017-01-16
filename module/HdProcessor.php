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
	//微信消息内容
	protected $message;

	public function __construct() {
		$this->config  = \Module::getModuleConfig();
		$this->message = $this->getMessage();
	}

	//回复方法
	abstract function handle( $rid );

	public function __call( $method, $arguments ) {
		$instance = \WeChat::instance( 'message' );
		if ( method_exists( $instance, $method ) ) {
			call_user_func_array( [ $instance, $method ], $arguments );
		}
	}
}