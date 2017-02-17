<?php namespace addons\yahoo\system;

/**
 * 模块配置管理
 * 用于管理当前模块的配置项
 * 每个模块配置是独立管理的互不影响
 * @author author
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
		$sql=<<<sql
CREATE TABLE `hd_a9999999999` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `data` varchar(2000) NOT NULL DEFAULT '' COMMENT '菜单数据',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL COMMENT '1 在微信生效 0 不在微信生效',
  `siteid` int(11) NOT NULL COMMENT '站点编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信菜单';
sql;
		Db::execute($sql);
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