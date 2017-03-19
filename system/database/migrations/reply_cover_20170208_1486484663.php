<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class reply_cover extends Migration {
    //执行
	public function up() {if(Schema::tableExists('reply_cover')){
      return;
    }
		$sql = <<<sql
CREATE TABLE `hd_reply_cover` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `rid` int(10) unsigned NOT NULL COMMENT '规则编号',
  `module` varchar(45) NOT NULL COMMENT '模块名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hash` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `rid` (`rid`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块封面关键词回复内容';
sql;
		Db::execute( $sql );
    }

    //回滚
    public function down() {
    }
}