<?php namespace addons\houdunren\system;

/**
 * 模块配置管理
 * 用于管理当前模块的配置项
 * 每个模块配置是独立管理的互不影响
 * @author 向军
 * @url http://www.hdcms.com
 */
use module\HdSetup;

class Setup extends HdSetup {
	/**
	 * 模块安装执行的方法
	 * 可以执行模块数据表安装语句
	 * 建议使用HDPHP中的Schema组件完成数据安装
	 */
	public function install() {
	}
	
	/**
	 * 卸载模块时执行的方法
	 * 可以执行模块数据表删除语句
	 * 建议使用HDPHP中的Schema组件完成数据安装
	 */
	public function uninstall() {
	}
	
	/**
	 * 模块更新时执行的方法
	 * 可以执行模块更新时修改数据表的语句
	 * 建议使用HDPHP中的Schema组件完成数据安装
	 */
	public function upgrade() {
	}
}