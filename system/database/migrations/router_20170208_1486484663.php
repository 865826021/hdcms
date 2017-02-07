<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class router extends Migration {
    //执行
	public function up() {
		$sql = <<<sql
CREATE TABLE `hd_router` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL COMMENT '站点编号',
  `module` int(11) NOT NULL COMMENT '模块标识',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '中文描述',
  `name` varchar(500) NOT NULL DEFAULT '' COMMENT '标识(正则表达式)',
  `router` mediumtext NOT NULL COMMENT '路由数据',
  `status` tinyint(1) unsigned NOT NULL COMMENT '开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='模块路由器设置';
sql;
		Db::execute( $sql );
    }

    //回滚
    public function down() {
    }
}