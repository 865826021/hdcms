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
}