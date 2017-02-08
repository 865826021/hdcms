<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class cache extends Migration {
    //执行
	public function up() {
		if(Schema::tableExists('cache')){
			return;
		}
		$sql = <<<sql
CREATE TABLE `hd_cache` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL DEFAULT '' COMMENT '标识名称',
  `data` mediumtext NOT NULL COMMENT '数据',
  `create_at` int(10) NOT NULL COMMENT '创建时间',
  `expire` int(10) NOT NULL COMMENT '过期时间',
  `siteid` int(11) NOT NULL COMMENT '站点编号 -1为系统配置0为无效 正数为站点编号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
sql;
		Db::execute( $sql );
    }

    //回滚
    public function down() {
    }
}