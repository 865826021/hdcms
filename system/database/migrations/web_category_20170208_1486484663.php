<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class web_category extends Migration {
    //执行
	public function up() {if(Schema::tableExists('web_category')){
      return;
    }
		$sql = <<<sql
CREATE TABLE `hd_web_category` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `mid` int(11) unsigned NOT NULL COMMENT '模型编号',
  `title` varchar(100) NOT NULL COMMENT '栏目标题',
  `pid` int(10) unsigned NOT NULL COMMENT '父级编号',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `icontype` tinyint(1) NOT NULL COMMENT '1 图标 2 图片',
  `description` varchar(255) NOT NULL COMMENT '栏目描述',
  `linkurl` varchar(300) NOT NULL COMMENT '外部链接',
  `ishomepage` tinyint(1) unsigned NOT NULL COMMENT '封面栏目',
  `css` varchar(500) NOT NULL COMMENT 'css样式',
  PRIMARY KEY (`cid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章分类';
sql;
		Db::execute( $sql );
    }

    //回滚
    public function down() {
    }
}