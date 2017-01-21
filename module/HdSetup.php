<?php namespace module;

/**
 * 模块业务基类
 * Class hdSite
 * @package system\core
 * @author 向军
 */
abstract class HdSetup {
	public function __construct() {
		\User::superUserAuth();
	}
}