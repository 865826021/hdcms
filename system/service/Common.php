<?php namespace system\service;
/**
 * 服务公共处理类
 * Class Common
 * @package system\service
 */
abstract class Common {
	//错误信息
	protected $error;

	public function getError() {
		return $this->error;
	}
}