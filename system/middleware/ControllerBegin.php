<?php namespace system\middleware;
use houdunwang\db\Db;
use houdunwang\route\Route;

/**
 * 控制器开始解析前
 * 根据路由解析变量处理控制器
 * Class ControllerBegin
 * @package system\middleware
 */
class ControllerBegin {
	//自动执行的方法
	public function run() {
		//初始站点数据
		\Site::siteInitialize();
		//初始模块数据
		\Module::moduleInitialize();
	}
}