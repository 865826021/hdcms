# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: houdunwang.mysql.rds.aliyuncs.com (MySQL 5.6.29)
# Database: dev
# Generation Time: 2017-02-07 14:13:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table hd_user_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hd_user_group`;

CREATE TABLE `hd_user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '组名',
  `maxsite` int(11) unsigned NOT NULL COMMENT '站点数量',
  `allfilesize` int(10) unsigned NOT NULL COMMENT '允许上传空间大小',
  `daylimit` int(11) unsigned NOT NULL COMMENT '有效期限',
  `package` varchar(2000) NOT NULL DEFAULT '' COMMENT '可使用的公众服务套餐',
  `system_group` tinyint(1) unsigned NOT NULL COMMENT '系统用户组',
  `router_num` int(11) unsigned NOT NULL COMMENT '允许设置路由的数量',
  `middleware_num` int(11) unsigned NOT NULL COMMENT '允许设置中间件的数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员组';

LOCK TABLES `hd_user_group` WRITE;
/*!40000 ALTER TABLE `hd_user_group` DISABLE KEYS */;

INSERT INTO `hd_user_group` (`id`, `name`, `maxsite`, `allfilesize`, `daylimit`, `package`, `system_group`, `router_num`, `middleware_num`)
VALUES
	(1,'体验组',100,0,30,'\"\"',1,100,100);

/*!40000 ALTER TABLE `hd_user_group` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
