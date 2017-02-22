<?php namespace addons\links\system;

/**
 * 模块配置管理
 * 用于管理当前模块的配置项
 * 每个模块配置是独立管理的互不影响
 * @author 后盾人团队
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
		$sql = <<<sql
CREATE TABLE `hd_links_lists` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `status` tinyint(4) unsigned NOT NULL COMMENT '状态',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='友情链接表';
sql;
		Db::execute( $sql );
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