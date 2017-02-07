# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: houdunwang.mysql.rds.aliyuncs.com (MySQL 5.6.29)
# Database: dev
# Generation Time: 2017-02-07 13:53:46 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table hd_profile_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hd_profile_fields`;

CREATE TABLE `hd_profile_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(45) NOT NULL COMMENT '字段名',
  `title` varchar(45) NOT NULL COMMENT '中文标题',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL COMMENT '启用',
  `required` tinyint(1) NOT NULL COMMENT '必须填写',
  `showinregister` tinyint(1) NOT NULL COMMENT '注册时显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员字段信息使用的基本数据表中文名称与状态';

LOCK TABLES `hd_profile_fields` WRITE;
/*!40000 ALTER TABLE `hd_profile_fields` DISABLE KEYS */;

INSERT INTO `hd_profile_fields` (`id`, `field`, `title`, `orderby`, `status`, `required`, `showinregister`)
VALUES
	(1,'qq','QQ号',0,0,1,1),
	(2,'realname','真实姓名',0,0,1,1),
	(3,'nickname','昵称',0,1,1,1),
	(4,'mobile','手机号码',0,1,1,1),
	(5,'telephone','固定电话',0,1,0,0),
	(6,'vip','VIP级别',0,1,0,0),
	(7,'address','居住地址',0,1,0,0),
	(8,'zipcode','邮编',0,1,0,0),
	(9,'alipay','阿里帐号',0,1,0,0),
	(10,'msn','msn帐号',0,1,0,0),
	(11,'taobao','淘宝帐号',0,1,0,0),
	(12,'email','邮箱',0,1,1,1),
	(13,'site','个人站点',0,1,0,0),
	(14,'nationality','国籍',0,1,0,0),
	(15,'introduce','自我介绍',0,1,0,0),
	(16,'gender','性别',0,1,0,0),
	(17,'graduateschool','毕业学校',0,1,0,0),
	(18,'height','身高',0,1,0,0),
	(19,'weight','体重',0,1,0,0),
	(20,'bloodtype','血型',0,1,0,0),
	(21,'birthyear','出生日期',0,1,0,0);

/*!40000 ALTER TABLE `hd_profile_fields` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
