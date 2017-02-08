<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class site_user extends Migration {
    //执行
	public function up() {if(Schema::tableExists('site_user')){
      return;
    }
		$sql = <<<sql
CREATE TABLE `hd_site_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT NULL COMMENT '用户id',
  `siteid` int(10) unsigned DEFAULT NULL COMMENT '站点id',
  `role` varchar(20) DEFAULT NULL COMMENT '角色类型：owner: 所有者 manage: 管理员  operate: 操作员',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点管理员';
sql;
		Db::execute( $sql );
    }

    //回滚
    public function down() {
    }
}