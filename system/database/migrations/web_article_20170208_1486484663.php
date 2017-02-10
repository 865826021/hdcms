<?php namespace system\database\migrations;

use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class web_article extends Migration {
	//执行
	public function up() {
		if ( Schema::tableExists( 'web_article' ) ) {
			return;
		}
		$sql = <<<sql
CREATE TABLE `hd_web_article` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '微信规则编号',
  `category_cid` int(10) unsigned NOT NULL COMMENT '栏目编号',
  `keyword` varchar(30) NOT NULL COMMENT '微信回复关键词',
  `iscommend` tinyint(1) unsigned NOT NULL COMMENT '推荐',
  `ishot` tinyint(1) unsigned NOT NULL COMMENT '头条',
  `title` varchar(145) NOT NULL COMMENT '标题',
  `click` mediumint(8) unsigned NOT NULL COMMENT '点击数',
  `thumb` varchar(300) NOT NULL COMMENT '缩略图',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` mediumtext NOT NULL COMMENT '内容',
  `source` varchar(45) NOT NULL COMMENT '来源',
  `author` varchar(45) NOT NULL COMMENT '作者',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `linkurl` varchar(145) NOT NULL COMMENT '外部链接地址',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`),
  KEY `category_cid` (`category_cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章模块内容表';
sql;
		Db::execute( $sql );
	}

	//回滚
	public function down() {
	}
}