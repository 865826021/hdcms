# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: houdunwang.mysql.rds.aliyuncs.com (MySQL 5.6.29)
# Database: dev
# Generation Time: 2017-02-07 18:00:14 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table hd_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hd_user`;

CREATE TABLE `hd_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `groupid` int(11) NOT NULL COMMENT '后台组',
  `username` char(30) NOT NULL COMMENT '用户名',
  `password` char(50) NOT NULL COMMENT '密码',
  `security` varchar(15) NOT NULL DEFAULT '' COMMENT '密钥',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `regtime` int(10) NOT NULL COMMENT '注册时间',
  `regip` varchar(15) NOT NULL COMMENT '注册ip',
  `lasttime` int(10) NOT NULL COMMENT '最后登录时间',
  `lastip` varchar(15) NOT NULL COMMENT '最后登录ip',
  `starttime` int(10) NOT NULL COMMENT '会员开始时间',
  `endtime` int(10) NOT NULL COMMENT '会员到期时间',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ号',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(20) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile_valid` tinyint(1) NOT NULL COMMENT '手机验证',
  `email_valid` tinyint(1) NOT NULL COMMENT '邮箱验证',
  `remark` varchar(300) NOT NULL COMMENT '备注',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `groupid` (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户';

LOCK TABLES `hd_user` WRITE;
/*!40000 ALTER TABLE `hd_user` DISABLE KEYS */;

INSERT INTO `hd_user` (`uid`, `groupid`, `username`, `password`, `security`, `status`, `regtime`, `regip`, `lasttime`, `lastip`, `starttime`, `endtime`, `qq`, `mobile`, `email`, `mobile_valid`, `email_valid`, `remark`)
VALUES
	(1,0,'admin','677e13412f2f6b983ccea4c60a810266','be7540ee9f',1,1465771582,'123.119.83.235',1486488154,'61.149.222.56',0,0,'232323','','',0,0,''),
	(2,1,'sdf','','',1,0,'',0,'',0,-28800,'','','',0,0,''),
	(3,1,'hdxj','f3c7bd94fad8aff363111c637dc61882','e3db7892d8',1,1483802870,'0.0.0.0',1483802882,'0.0.0.0',1483802870,1484323200,'','','',0,0,''),
	(4,1,'sina','9f94700ee3c183c6d5c0570a5812651e','a8e3b9f509',1,1483886758,'0.0.0.0',1483886758,'0.0.0.0',1483886758,1484491558,'23892398','18711655565','sdksdlksdl@ds.com',0,0,'');

/*!40000 ALTER TABLE `hd_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
