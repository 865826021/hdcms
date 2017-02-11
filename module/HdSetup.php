<?php namespace module;

/**
 * 模块安装管理基类
 * Class HdSetup
 * @package module
 */
abstract class HdSetup {
	public function __construct() {
		\User::superUserAuth();
	}
}