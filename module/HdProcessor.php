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
	protected $content;
	//微信消息管理实例
	protected $message;

	public function __construct() {
		$this->config  = \Module::getModuleConfig();
		$this->message = \WeChat::instance( 'message' );
		$this->content = $this->message->getMessage();
	}

	//回复方法
	abstract function handle( $rid );

	public function __call( $method, $arguments = [ ] ) {
		return call_user_func_array( [ $this->message, $method ], $arguments );
	}
}