<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class navigate extends Migration {
    //执行
	public function up() {if(Schema::tableExists('navigate')){
      return;
    }
		$sql = <<<sql
CREATE TABLE `hd_navigate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `webid` int(10) unsigned NOT NULL COMMENT '微站编号',
  `module` varchar(100) NOT NULL COMMENT '模块',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `url` varchar(100) NOT NULL COMMENT '链接',
  `css` text NOT NULL COMMENT '样式',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `category_cid` int(10) unsigned NOT NULL COMMENT '栏目编号',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `position` tinyint(4) unsigned NOT NULL COMMENT '位置',
  `orderby` tinyint(4) unsigned NOT NULL COMMENT '排序',
  `icontype` tinyint(3) unsigned NOT NULL COMMENT '图标类型 1字体 2 图片',
  `entry` varchar(10) NOT NULL DEFAULT '' COMMENT 'home 微站首页导航  profile 手机会员中心导航 member 桌面会员中心导航',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `web_id` (`webid`),
  KEY `category_cid` (`category_cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='导航菜单管理表';
sql;
		Db::execute( $sql );
    }

    //回滚
    public function down() {
    }
}