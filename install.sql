DROP TABLE IF EXISTS `hd_balance`;

CREATE TABLE `hd_balance` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `tid` varchar(80) NOT NULL,
  `fee` decimal(10,2) NOT NULL COMMENT '金额',
  `status` tinyint(1) NOT NULL COMMENT '状态 0 等待支付 1 支付成功',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`bid`),
  KEY `siteid` (`siteid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员余额充值';

DROP TABLE IF EXISTS `hd_button`;

CREATE TABLE `hd_button` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `data` varchar(2000) NOT NULL DEFAULT '' COMMENT '菜单数据',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL COMMENT '1 在微信生效 0 不在微信生效',
  `siteid` int(11) NOT NULL COMMENT '站点编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信菜单';

DROP TABLE IF EXISTS `hd_cloud`;

CREATE TABLE `hd_cloud` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL DEFAULT '' COMMENT '平台帐号',
  `token` varchar(100) NOT NULL DEFAULT '' COMMENT '令牌',
  `version` varchar(45) NOT NULL COMMENT '版本号',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='与平台有关的设置';

DROP TABLE IF EXISTS `hd_config`;
CREATE TABLE `hd_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site` text NOT NULL COMMENT '网站开启/登录等设置',
  `upload` text NOT NULL COMMENT '上传设置',
  `register` text NOT NULL COMMENT '注册配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置';

INSERT INTO `hd_config` (`id`, `site`, `upload`, `register`)
VALUES
	(1,'{\"is_open\":\"1\",\"enable_code\":0,\"close_message\":\"网站维护中,请稍候访问\"}','','{\"is_open\":1,\"audit\":\"1\",\"enable_code\":0,\"groupid\":2}');

DROP TABLE IF EXISTS `hd_core_attachment`;
CREATE TABLE `hd_core_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '会员id',
  `siteid` int(11) NOT NULL COMMENT '站点编号',
  `name` varchar(80) NOT NULL,
  `filename` varchar(300) NOT NULL COMMENT '文件名',
  `path` varchar(300) NOT NULL COMMENT '相对路径',
  `extension` varchar(10) NOT NULL DEFAULT '' COMMENT '类型',
  `createtime` int(10) NOT NULL COMMENT '上传时间',
  `size` mediumint(9) NOT NULL COMMENT '文件大小',
  `is_member` tinyint(1) NOT NULL COMMENT '1 前台 2 后台',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='附件';

DROP TABLE IF EXISTS `hd_core_cache`;
CREATE TABLE `hd_core_cache` (
  `key` varchar(100) NOT NULL COMMENT '缓存名称',
  `value` mediumtext NOT NULL COMMENT '缓存数据',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='缓存';

DROP TABLE IF EXISTS `hd_core_config`;
CREATE TABLE `hd_core_config` (
  `key` varchar(100) NOT NULL COMMENT '配置名称',
  `value` text NOT NULL COMMENT '配置项',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='配置  todo';

DROP TABLE IF EXISTS `hd_core_session`;
CREATE TABLE `hd_core_session` (
  `sessid` char(50) NOT NULL,
  `data` mediumtext COMMENT 'session数据',
  `atime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`sessid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='session';

DROP TABLE IF EXISTS `hd_credits_record`;
CREATE TABLE `hd_credits_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `credittype` varchar(45) NOT NULL COMMENT '积分类型',
  `num` decimal(10,2) NOT NULL COMMENT '数量',
  `operator` int(10) unsigned NOT NULL COMMENT '操作者编号',
  `module` varchar(45) NOT NULL COMMENT '模块名',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `uid` (`uid`),
  KEY `operator` (`operator`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='积分变动记录';

DROP TABLE IF EXISTS `hd_member`;
CREATE TABLE `hd_member` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `email` varchar(45) NOT NULL COMMENT '邮箱',
  `password` varchar(45) NOT NULL COMMENT '密码',
  `openid` varchar(80) NOT NULL DEFAULT '' COMMENT '微信openid',
  `security` varchar(15) NOT NULL COMMENT '安装密钥',
  `group_id` int(10) unsigned NOT NULL COMMENT '会员组编号',
  `icon` varchar(300) NOT NULL DEFAULT '' COMMENT '头像',
  `credit1` decimal(10,2) NOT NULL COMMENT '积分',
  `credit2` decimal(10,2) NOT NULL COMMENT '余额',
  `credit3` decimal(10,2) NOT NULL,
  `credit4` decimal(10,2) NOT NULL,
  `credit5` decimal(10,2) NOT NULL,
  `credit6` decimal(10,2) NOT NULL,
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'QQ号',
  `realname` varchar(15) NOT NULL COMMENT '真实姓名',
  `nickname` varchar(45) NOT NULL COMMENT '昵称',
  `telephone` varchar(15) NOT NULL COMMENT '固定电话',
  `vip` tinyint(3) unsigned NOT NULL COMMENT 'VIP级别',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `zipcode` varchar(10) NOT NULL COMMENT '邮编',
  `alipay` varchar(45) NOT NULL COMMENT '阿里帐号',
  `msn` varchar(45) NOT NULL COMMENT 'msn帐号',
  `taobao` varchar(45) NOT NULL COMMENT '淘宝帐号',
  `site` varchar(45) NOT NULL COMMENT '个人站点',
  `nationality` varchar(45) NOT NULL COMMENT '国籍',
  `introduce` varchar(200) NOT NULL COMMENT '自我介绍',
  `gender` tinyint(1) NOT NULL COMMENT '性别',
  `graduateschool` varchar(45) NOT NULL COMMENT '毕业学校',
  `height` varchar(10) NOT NULL COMMENT '身高',
  `weight` varchar(10) NOT NULL COMMENT '体重',
  `bloodtype` varchar(5) NOT NULL COMMENT '血型',
  `birthyear` int(10) unsigned NOT NULL COMMENT '出生年',
  `birthmonth` tinyint(3) unsigned NOT NULL COMMENT '出生月',
  `birthday` tinyint(3) unsigned NOT NULL COMMENT '出生日',
  `resideprovince` varchar(30) NOT NULL DEFAULT '' COMMENT '户籍省',
  `residecity` varchar(30) NOT NULL DEFAULT '' COMMENT '户籍市',
  `residedist` varchar(30) NOT NULL DEFAULT '' COMMENT '户籍区',
  PRIMARY KEY (`uid`),
  KEY `group_id` (`group_id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员表';

DROP TABLE IF EXISTS `hd_member_address`;
CREATE TABLE `hd_member_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `username` varchar(20) NOT NULL COMMENT '姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `zipcode` varchar(6) NOT NULL COMMENT '邮编',
  `province` varchar(45) NOT NULL COMMENT '省',
  `city` varchar(45) NOT NULL COMMENT '市',
  `district` varchar(45) NOT NULL COMMENT '区/县',
  `address` varchar(45) NOT NULL COMMENT '街道名称',
  `isdefault` tinyint(1) unsigned NOT NULL COMMENT '默认',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员收货地址';

DROP TABLE IF EXISTS `hd_member_fields`;
CREATE TABLE `hd_member_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `field` varchar(45) NOT NULL COMMENT '字段名',
  `title` varchar(45) NOT NULL COMMENT '中文标题',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL COMMENT '启用',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员字段信息中文名称与状态';

DROP TABLE IF EXISTS `hd_member_group`;
CREATE TABLE `hd_member_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `title` varchar(45) NOT NULL COMMENT '组名',
  `credit` int(10) unsigned NOT NULL COMMENT '积分',
  `rank` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `isdefault` tinyint(1) unsigned NOT NULL COMMENT '默认会员组',
  `is_system` tinyint(1) unsigned NOT NULL COMMENT '系统组',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `hd_member_group` (`id`, `siteid`, `title`, `credit`, `rank`, `isdefault`, `is_system`)
VALUES
	(1,1,'普通会员',0,0,1,1);

DROP TABLE IF EXISTS `hd_menu`;
CREATE TABLE `hd_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级',
  `title` varchar(50) NOT NULL COMMENT '中文标题',
  `permission` varchar(100) NOT NULL DEFAULT '' COMMENT '权限标识',
  `url` varchar(100) DEFAULT NULL COMMENT '菜单链接',
  `append_url` varchar(100) NOT NULL COMMENT '右侧图标链接',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '右侧菜单图标',
  `orderby` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显示',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '系统菜单',
  `mark` varchar(45) DEFAULT NULL COMMENT '标识',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='菜单';

INSERT INTO `hd_menu` (`id`, `pid`, `title`, `permission`, `url`, `append_url`, `icon`, `orderby`, `is_display`, `is_system`, `mark`)
VALUES
	(1,0,'基础设置','','?s=site/entry/home&p=platform','','fa fa-comments-o',0,1,0,'platform'),
	(2,1,'基本功能','','','','',0,1,0,'platform'),
	(3,2,'文字回复','reply_basic','?s=site/reply/lists&m=basic','?s=site/reply/post&m=basic','fa fa-plus',0,1,0,'platform'),
	(21,0,'扩展模块','','?s=site/entry/home&p=package','','fa fa-arrows',100,1,0,'package'),
	(27,21,'管理','','','','',0,1,0,'package'),
	(30,2,'图文回复','reply_news','?s=site/reply/lists&m=news','?s=site/reply/post&m=news','fa fa-plus',0,1,0,'platform'),
	(31,0,'功能选项','','?s=site/entry/home&p=feature','','fa fa-comments-o',20,1,0,'feature'),
	(32,0,'会员粉丝','','?s=site/entry/home&p=member','','fa fa-cubes',10,1,0,'member'),
	(33,32,'会员中心','','','','fa fa-cubes',0,1,0,'member'),
	(35,33,'会员','users','?a=site/MemberLists&t=site&m=member','?a=site/MemberPost&t=site&m=member','fa fa-cubes',0,1,0,'member'),
	(36,33,'会员组','member_groups','?a=site/GroupLists&t=site&m=member','?a=site/GroupPost&t=site&m=member','fa fa-cubes',0,1,0,'member'),
	(38,32,'积分兑换','','','','fa fa-cubes',0,1,0,'member'),
	(39,38,'折扣券','member_coupons','?a=site/lists&t=site&type=1&m=ticket','?a=site/post&t=site&type=1&m=ticket','fa fa-cubes',0,1,0,'member'),
	(40,38,'折扣券核销','member_coupons_charge','?a=site/charge&t=site&type=1&m=ticket','','fa fa-cubes',0,1,0,'member'),
	(41,38,'代金券','member_cash','?a=site/lists&t=site&type=2&m=ticket','?a=site/post&t=site&type=2&m=ticket','fa fa-cubes',0,1,0,'member'),
	(42,38,'代金券核销','member_cash_charge','?a=site/charge&t=site&type=2&m=ticket','','fa fa-cubes',0,1,0,'member'),
	(55,2,'系统回复','reply_special','?a=site/post&t=site&m=special','','fa fa-cubes',0,1,0,'platform'),
	(63,31,'公众号选项','','','','fa fa-cubes',0,1,0,'feature'),
	(64,63,'支付参数','setting_pay','?a=site/pay&t=site&m=setting','','fa fa-cubes',0,1,0,'feature'),
	(66,31,'会员与粉丝选项','','','','fa fa-cubes',0,1,0,'feature'),
	(67,66,'积分设置','setting_credit','?a=site/credit&t=site&m=setting','','fa fa-cubes',0,1,0,'feature'),
	(68,66,'注册设置','setting_register','?a=site/register&t=site&m=setting','','fa fa-cubes',0,1,0,'feature'),
	(70,66,'邮件通知设置','setting_mail','?a=site/mail&t=site&m=setting','','fa fa-cubes',0,1,0,'feature'),
	(71,0,'文章系统','','?s=site/entry/home&p=article','','fa fa-cubes',0,1,0,'article'),
	(72,71,'官网管理','','?s=article/home/welcome','','fa fa-cubes',0,1,0,'article'),
	(73,72,'官网模板','article_manage_template','?a=manage/template&t=site&m=article','','fa fa-cubes',0,1,0,'article'),
	(74,71,'内容管理','','','','fa fa-cubes',0,1,0,'article'),
	(75,74,'分类管理','article_content_category','?a=content/category&t=site&m=article','?a=content/categoryPost&t=site&m=article','fa fa-cubes',0,1,0,'article'),
	(76,74,'文章管理','article_content_article','?a=content/article&t=site&m=article','?a=content/articlePost&t=site&m=article','fa fa-cubes',0,1,0,'article'),
	(77,72,'站点管理','article_manage_site','?a=manage/site&t=site&m=article','?a=manage/SitePost&t=site&m=article','fa fa-cubes',0,1,0,'article'),
	(78,71,'特殊页面管理','','','','fa fa-cubes',0,1,0,'article'),
	(80,78,'会员中心','article_ucenter_post','?a=ucenter/post&t=site&m=article','','fa fa-cubes',0,1,0,'article'),
	(81,27,'扩展功能管理','package_managa','?s=site/entry/package','','fa fa-cubes',0,1,0,'package'),
	(82,1,'高级功能','','','','fa fa-cubes',0,1,0,'platform'),
	(84,33,'会员字段管理','member_fields','?a=site/Fieldlists&t=site&m=member','','fa fa-cubes',0,1,0,'member'),
	(85,78,'微站快捷导航','article_quick_menu','?a=quickmenu/menu&t=site&m=article','','fa fa-cubes',0,1,0,'article'),
	(86,82,'微信菜单','menus_lists','?a=site/lists&t=site&m=button','','fa fa-cubes',0,1,0,'platform');

DROP TABLE IF EXISTS `hd_module_setting`;
CREATE TABLE `hd_module_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点名称',
  `module` varchar(45) NOT NULL COMMENT '模块名称',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `setting` text NOT NULL COMMENT '配置',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块配置';

DROP TABLE IF EXISTS `hd_modules`;
CREATE TABLE `hd_modules` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '英文标识',
  `industry` varchar(45) NOT NULL COMMENT '行业类型 business(主要业务)customer(客户关系)marketing(营销与活动)tools(常用服务与工具)industry(行业解决方案)other(其他)',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '模块名称(中文标题)',
  `version` varchar(45) NOT NULL COMMENT '版本号',
  `resume` varchar(1000) NOT NULL COMMENT '模块简述',
  `detail` varchar(500) NOT NULL DEFAULT '' COMMENT '详细介绍',
  `author` varchar(45) NOT NULL COMMENT '作者',
  `url` varchar(255) NOT NULL COMMENT '发布url',
  `is_system` varchar(45) NOT NULL DEFAULT '' COMMENT '系统模块',
  `subscribes` varchar(500) NOT NULL COMMENT '订阅信息',
  `processors` varchar(500) NOT NULL DEFAULT '' COMMENT '处理消息',
  `setting` tinyint(1) unsigned NOT NULL COMMENT '存在全局设置项',
  `rule` tinyint(1) unsigned NOT NULL COMMENT '需要嵌入规则',
  `permissions` varchar(5000) NOT NULL DEFAULT '' COMMENT '业务规则权限验证标识',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '模块缩略图',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '模块封面图',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `name` (`name`),
  KEY `is_system` (`is_system`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块列表';

INSERT INTO `hd_modules` (`mid`, `name`, `industry`, `title`, `version`, `resume`, `detail`, `author`, `url`, `is_system`, `subscribes`, `processors`, `setting`, `rule`, `permissions`, `thumb`, `cover`)
VALUES
	(1,'basic','business','基本文字回复','1.0','和您进行简单对话','一问一答得简单对话. 当访客的对话语句中包含指定关键字, 或对话语句完全等于特定关键字, 将回复文字或链接给用户','后盾','http://open.hdcms.com','1','','a:1:{i:0;s:4:\"text\";}',0,0,'','thumb.jpg','cover.jpg'),
	(2,'news','business','基本混合图文回复','1.0','为你提供生动的图文资讯','一问一答得简单对话. 当访客的对话语句中包含指定关键字, 或对话语句完全等于特定关键字, 将回复文字或链接给用户','后盾','http://open.hdcms.com','1','','a:1:{i:0;s:4:\"text\";}',0,0,'','thumb.jpg','cover.jpg'),
	(3,'article','business','文章系统','1.0','发布文章与会员中心管理','支持桌面、移动、微信三网的文章系统，同时具有移动、桌面会员中心管理功能','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg'),
	(4,'setting','business','网站配置','1.0','网站运行整体配置','网站运行配置项，如支付、邮箱、登录等等的全局配置项管理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg'),
	(5,'member','business','会员粉丝','1.0','会员管理','会员与会员组管理，如会员字段，粉丝管理、会员卡设置','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg'),
	(6,'special','business','微信默认消息','1.0','微信默认消息','系统默认消息与关注微信消息处理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg'),
	(7,'ticket','business','卡券管理','1.0','会员卡券管理','会员优惠券、代金券、实物券管理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg'),
	(8,'cover','business','封面回复','1.0','封面消息回复','用来处理模块的封面消息','后盾','http://open.hdcms.com','1','','a:1:{i:0;s:4:\"text\";}',0,0,'','thumb.png','cover.jpg'),
	(9,'uc','business','会员中心','1.0','会员中心的管理操作','会员信息的管理，包括收货地址、个人资料、会员卡券等管理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.png','cover.jpg'),
	(10,'button','business','微信菜单','1.0','微信菜单管理','用于添加微信菜单，更新菜单后需要取消关注再关注或等微信更新缓存后有效','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg');

DROP TABLE IF EXISTS `hd_modules_bindings`;
CREATE TABLE `hd_modules_bindings` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(45) NOT NULL DEFAULT '' COMMENT '模块名称',
  `entry` varchar(45) NOT NULL DEFAULT '' COMMENT '类型 封面、回复规则列表、业务菜单',
  `title` varchar(45) NOT NULL DEFAULT '' COMMENT '中文标题',
  `do` varchar(45) NOT NULL DEFAULT '' COMMENT '动作方法',
  `data` varchar(255) NOT NULL DEFAULT '' COMMENT '附加参数',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '自定义菜单的url',
  `icon` varchar(80) NOT NULL DEFAULT '' COMMENT '自定义菜单的图标图标',
  `orderby` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `directly` tinyint(11) unsigned NOT NULL COMMENT '无需登陆直接展示',
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块动作';

DROP TABLE IF EXISTS `hd_package`;
CREATE TABLE `hd_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '套餐名称',
  `modules` varchar(5000) NOT NULL COMMENT '允许使用的套餐',
  `template` varchar(5000) NOT NULL COMMENT '允许使用的模板',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='套餐';

DROP TABLE IF EXISTS `hd_pay`;
CREATE TABLE `hd_pay` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `tid` varchar(80) NOT NULL COMMENT '定单编号',
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `type` varchar(45) NOT NULL COMMENT '支付类型，alipay wechat',
  `fee` decimal(10,2) NOT NULL COMMENT '支付金额',
  `status` tinyint(4) unsigned NOT NULL COMMENT '状态 0 未支付 1 已支付',
  `module` varchar(45) NOT NULL COMMENT '模块名称',
  `use_card` tinyint(3) unsigned NOT NULL COMMENT '使用卡券',
  `card_type` tinyint(3) unsigned NOT NULL COMMENT '卡券类型',
  `card_id` int(10) unsigned NOT NULL COMMENT '卡券编号',
  `card_fee` decimal(10,2) NOT NULL COMMENT '卡券金额',
  `goods_name` varchar(300) NOT NULL COMMENT '商品名称',
  `attach` varchar(300) NOT NULL COMMENT '附加数据',
  `body` varchar(300) NOT NULL COMMENT '商品描述',
  PRIMARY KEY (`pid`),
  KEY `siteid` (`siteid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='支付记录';

DROP TABLE IF EXISTS `hd_profile_fields`;
CREATE TABLE `hd_profile_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(45) NOT NULL COMMENT '字段名',
  `title` varchar(45) NOT NULL COMMENT '中文标题',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `required` tinyint(1) NOT NULL COMMENT '必须填写',
  `showinregister` tinyint(1) NOT NULL COMMENT '注册时显示',
  `status` tinyint(1) NOT NULL COMMENT '使用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='个人信息中文名称与状态';


INSERT INTO `hd_profile_fields` (`id`, `field`, `title`, `orderby`, `required`, `showinregister`, `status`)
VALUES
	(1,'qq','QQ号',0,1,1,1),
	(2,'realname','真实姓名',0,1,1,1),
	(3,'nickname','昵称',0,1,1,1),
	(4,'mobile','手机号码',0,1,1,1),
	(5,'telephone','固定电话',0,0,0,1),
	(6,'vip','VIP级别',0,0,0,1),
	(7,'address','居住地址',0,0,0,1),
	(8,'zipcode','邮编',0,0,0,1),
	(9,'alipay','阿里帐号',0,0,0,1),
	(10,'msn','msn帐号',0,0,0,1),
	(11,'taobao','淘宝帐号',0,0,0,1),
	(12,'email','邮箱',0,1,1,1),
	(13,'site','个人站点',0,0,0,1),
	(14,'nationality','国籍',0,0,0,1),
	(15,'introduce','自我介绍',0,0,0,1),
	(16,'gender','性别',0,0,0,1),
	(17,'graduateschool','毕业学校',0,0,0,1),
	(18,'height','身高',0,0,0,1),
	(19,'weight','体重',0,0,0,1),
	(20,'bloodtype','血型',0,0,0,1),
	(21,'birthyear','出生日期',0,0,0,1);

DROP TABLE IF EXISTS `hd_reply_basic`;
CREATE TABLE `hd_reply_basic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `rid` int(10) unsigned NOT NULL COMMENT '规则编号',
  `content` varchar(500) NOT NULL COMMENT '回复内容',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='普通文本回复';

DROP TABLE IF EXISTS `hd_reply_cover`;
CREATE TABLE `hd_reply_cover` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `web_id` int(11) unsigned NOT NULL COMMENT '微站编号',
  `rid` int(10) unsigned NOT NULL COMMENT '规则编号',
  `module` varchar(45) NOT NULL COMMENT '模块名称',
  `do` varchar(45) NOT NULL COMMENT '模块动作',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块封面关键词回复内容';

DROP TABLE IF EXISTS `hd_reply_image`;
CREATE TABLE `hd_reply_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `rid` int(10) unsigned NOT NULL COMMENT '规则编号',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `mediaid` varchar(255) NOT NULL COMMENT '微信medicaid',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `hd_reply_news`;
CREATE TABLE `hd_reply_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `rid` int(10) unsigned NOT NULL COMMENT '规则编号',
  `pid` int(10) unsigned NOT NULL COMMENT '父级',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `author` varchar(45) DEFAULT NULL COMMENT '作者',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `content` mediumtext NOT NULL COMMENT '内容',
  `url` varchar(255) NOT NULL COMMENT '链接地址',
  `rank` tinyint(255) unsigned NOT NULL COMMENT '排序',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='图文回复';

DROP TABLE IF EXISTS `hd_rule`;
CREATE TABLE `hd_rule` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '规则名称',
  `module` varchar(45) NOT NULL DEFAULT '' COMMENT '模块名称',
  `rank` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL COMMENT '是否禁用',
  PRIMARY KEY (`rid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='回复规则';

DROP TABLE IF EXISTS `hd_rule_keyword`;
CREATE TABLE `hd_rule_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则编号',
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `module` varchar(45) NOT NULL COMMENT '模块名称',
  `content` varchar(200) NOT NULL COMMENT '关键词内容',
  `type` varchar(45) NOT NULL COMMENT '关键词类型 1: 完全匹配  2:包含  3:正则 4:直接托管',
  `rank` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL COMMENT '是否开启',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='回复规则关键词';

DROP TABLE IF EXISTS `hd_site`;
CREATE TABLE `hd_site` (
  `siteid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '站点名称',
  `weid` int(10) unsigned NOT NULL COMMENT '微信编号',
  `allfilesize` int(11) DEFAULT NULL COMMENT '服务器可使用的空间大小',
  `createtime` int(10) DEFAULT NULL COMMENT '站点创建时间',
  PRIMARY KEY (`siteid`),
  KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点信息';

DROP TABLE IF EXISTS `hd_site_modules`;
CREATE TABLE `hd_site_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `module` varchar(45) DEFAULT NULL COMMENT '模块名称',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点扩展模块';

DROP TABLE IF EXISTS `hd_site_package`;
CREATE TABLE `hd_site_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `package_id` int(10) NOT NULL COMMENT '套餐编号',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点可以使用的套餐';

DROP TABLE IF EXISTS `hd_site_setting`;
CREATE TABLE `hd_site_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `grouplevel` tinyint(1) unsigned NOT NULL COMMENT '会员组变更设置 1 不自动变更   2根据总积分多少自动升降   3 根据总积分多少只升不降',
  `default_template` tinyint(1) unsigned NOT NULL COMMENT '默认网站模板',
  `creditnames` varchar(1000) NOT NULL COMMENT '积分名称',
  `creditbehaviors` varchar(1000) NOT NULL COMMENT '积分策略',
  `welcome` varchar(60) NOT NULL COMMENT '用户添加公众帐号时发送的欢迎信息',
  `default_message` varchar(60) NOT NULL COMMENT '系统不知道该如何回复粉丝的消息时默认发送的内容',
  `register` varchar(2000) NOT NULL DEFAULT '' COMMENT '注册设置',
  `smtp` varchar(2000) NOT NULL DEFAULT '' COMMENT '邮件通知',
  `pay` varchar(2000) NOT NULL DEFAULT '' COMMENT '支付设置',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点设置';

DROP TABLE IF EXISTS `hd_site_template`;
CREATE TABLE `hd_site_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `template` varchar(45) DEFAULT NULL COMMENT '模块名称',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点扩展模板';

DROP TABLE IF EXISTS `hd_site_user`;
CREATE TABLE `hd_site_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT NULL COMMENT '用户id',
  `siteid` int(10) unsigned DEFAULT NULL COMMENT '站点id',
  `role` varchar(20) DEFAULT NULL COMMENT '角色类型：owner: 所有者 manage: 管理员  operate: 操作员',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点管理员';

DROP TABLE IF EXISTS `hd_site_wechat`;
CREATE TABLE `hd_site_wechat` (
  `weid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `wename` varchar(45) NOT NULL COMMENT '微信名称',
  `account` varchar(45) NOT NULL COMMENT '公众号帐号',
  `original` varchar(45) NOT NULL COMMENT '原始ID',
  `level` tinyint(1) unsigned NOT NULL COMMENT '级别 1:普通订阅号 2:普通服务号 3:认证订阅号 4:认证服务号/认证媒体/政府订阅号',
  `appid` varchar(100) NOT NULL COMMENT 'AppId',
  `appsecret` varchar(100) NOT NULL COMMENT 'AppSecret',
  `qrcode` varchar(200) NOT NULL COMMENT '二维码图片',
  `icon` varchar(200) NOT NULL COMMENT '头像',
  `is_connect` tinyint(1) NOT NULL COMMENT '公众号接入状态',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT '微信平台token',
  `encodingaeskey` varchar(50) NOT NULL DEFAULT '' COMMENT '微信平台encodingaeskey',
  PRIMARY KEY (`weid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信帐号';

DROP TABLE IF EXISTS `hd_template`;
CREATE TABLE `hd_template` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '模板名称',
  `title` varchar(200) NOT NULL COMMENT '中文标题',
  `version` varchar(45) NOT NULL COMMENT '版本号',
  `description` varchar(500) NOT NULL COMMENT '模板描述',
  `author` varchar(45) NOT NULL COMMENT '作者',
  `url` varchar(300) NOT NULL COMMENT '发布页URL地址',
  `type` varchar(45) NOT NULL COMMENT '行业类型 hotel(酒店) car(汽车) tour(旅游) real(房地产) medical(医疗) 教育(edu) beauty(美容健身) photography(婚纱摄影) other(其他行业)',
  `position` tinyint(4) unsigned NOT NULL COMMENT '位置 ',
  `is_system` tinyint(1) unsigned NOT NULL COMMENT '系统模板',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '模板缩略图',
  `is_default` tinyint(1) NOT NULL COMMENT '默认模板',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点模板';

INSERT INTO `hd_template` (`tid`, `name`, `title`, `version`, `description`, `author`, `url`, `type`, `position`, `is_system`, `thumb`, `is_default`)
VALUES
	(1,'default','默认模板','1.9','HDCMS 默认模板','后盾人','http://open.hdcms.com','other',10,1,'thumb.jpg',0);

DROP TABLE IF EXISTS `hd_ticket`;
CREATE TABLE `hd_ticket` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `sn` varchar(45) NOT NULL COMMENT 'SN',
  `type` tinyint(1) unsigned NOT NULL COMMENT '1 代金券 2 折扣券',
  `condition` decimal(10,2) NOT NULL COMMENT '满多少钱可以使用',
  `discount` decimal(10,2) NOT NULL COMMENT '可享受的折扣或现金优惠',
  `thumb` varchar(300) NOT NULL COMMENT '缩略图',
  `description` text NOT NULL COMMENT '文字描述',
  `credittype` varchar(20) NOT NULL COMMENT '积分类型',
  `credit` int(10) unsigned NOT NULL COMMENT '积分数量',
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `limit` int(10) unsigned NOT NULL COMMENT '每人可使用的数量',
  `amount` int(10) unsigned NOT NULL COMMENT '券的总数量',
  PRIMARY KEY (`tid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='折扣券';

DROP TABLE IF EXISTS `hd_ticket_groups`;
CREATE TABLE `hd_ticket_groups` (
  `tgid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点id',
  `tid` int(10) unsigned NOT NULL COMMENT '优惠券或代金券编号',
  `group_id` int(10) unsigned NOT NULL COMMENT '会员组编号',
  PRIMARY KEY (`tgid`),
  KEY `siteid` (`siteid`),
  KEY `tid` (`tid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='代金券和优惠券可使用的会员组';

DROP TABLE IF EXISTS `hd_ticket_module`;
CREATE TABLE `hd_ticket_module` (
  `tmid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL COMMENT '券编号',
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `module` varchar(100) NOT NULL DEFAULT '' COMMENT '模块名称',
  PRIMARY KEY (`tmid`),
  KEY `tid` (`tid`),
  KEY `siteid` (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='代金券或优惠券可使用的模块';

DROP TABLE IF EXISTS `hd_ticket_record`;
CREATE TABLE `hd_ticket_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `tid` int(10) unsigned NOT NULL COMMENT '卡券编号',
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `createtime` int(10) NOT NULL COMMENT '兑换卡券时间',
  `usetime` int(10) NOT NULL COMMENT '使用时间',
  `module` varchar(45) NOT NULL COMMENT '使用模块 system 为系统核销',
  `remark` varchar(200) NOT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL COMMENT '状态 1 未使用 2 使用',
  `manage` int(10) unsigned NOT NULL COMMENT '核销员编号',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `uid` (`uid`),
  KEY `siteid` (`siteid`),
  KEY `manage` (`manage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='折扣券与代金券使用记录';

DROP TABLE IF EXISTS `hd_user`;
CREATE TABLE `hd_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `groupid` int(11) NOT NULL,
  `username` char(30) NOT NULL COMMENT '用户名',
  `password` char(50) NOT NULL COMMENT '密码',
  `security` varchar(15) NOT NULL,
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
  `remark` varchar(300) NOT NULL COMMENT '备注',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `groupid` (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户';

INSERT INTO `hd_user` (`uid`, `groupid`, `username`, `password`, `security`, `status`, `regtime`, `regip`, `lasttime`, `lastip`, `starttime`, `endtime`, `qq`, `mobile`, `email`, `remark`)
VALUES
	(1,0,'admin','e155336561b4a25e34e7a01409dba680','c028b5ef0f',1,1465771582,'123.119.83.235',1468914277,'192.168.10.1',1465771582,1465771582,'','','','系统管理员帐号');

DROP TABLE IF EXISTS `hd_user_group`;
CREATE TABLE `hd_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '组名',
  `maxsite` int(11) DEFAULT NULL COMMENT '站点数量',
  `daylimit` int(11) DEFAULT NULL COMMENT '有效期限',
  `package` varchar(2000) DEFAULT NULL COMMENT '可使用的公众服务套餐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员组';

INSERT INTO `hd_user_group` (`id`, `name`, `maxsite`, `daylimit`, `package`)
VALUES
	(1,'体验用户组',3,16,'a:2:{i:0;s:2:\"-1\";i:1;s:2:\"12\";}');

DROP TABLE IF EXISTS `hd_user_permission`;
CREATE TABLE `hd_user_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `siteid` int(10) unsigned NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'system 系统  具体模块名如houdun guaguaka',
  `permission` varchar(1000) NOT NULL COMMENT '权限内容以|分隔',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `siteid` (`siteid`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户权限分配';

DROP TABLE IF EXISTS `hd_user_profile`;
CREATE TABLE `hd_user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `qq` varchar(15) NOT NULL DEFAULT '',
  `realname` varchar(15) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(45) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `vip` tinyint(3) unsigned NOT NULL COMMENT 'VIP级别',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '邮寄地址',
  `zipcode` varchar(10) NOT NULL DEFAULT '' COMMENT '邮编',
  `alipay` varchar(45) NOT NULL DEFAULT '' COMMENT '阿里帐号',
  `msn` varchar(45) NOT NULL DEFAULT '' COMMENT 'msn帐号',
  `taobao` varchar(45) NOT NULL DEFAULT '' COMMENT '淘宝帐号',
  `email` varchar(45) NOT NULL DEFAULT '' COMMENT '邮箱',
  `site` varchar(45) NOT NULL DEFAULT '' COMMENT '个人站点',
  `nationality` varchar(45) NOT NULL DEFAULT '' COMMENT '国籍',
  `introduce` varchar(200) NOT NULL DEFAULT '' COMMENT '自我介绍',
  `gender` varchar(10) NOT NULL DEFAULT '' COMMENT '性别',
  `graduateschool` varchar(45) NOT NULL DEFAULT '' COMMENT '毕业学校',
  `height` varchar(10) NOT NULL DEFAULT '' COMMENT '身高',
  `weight` varchar(10) NOT NULL DEFAULT '' COMMENT '体重',
  `bloodtype` varchar(5) NOT NULL DEFAULT '' COMMENT '血型',
  `birthyear` smallint(6) NOT NULL COMMENT '出生年',
  `birthmonth` tinyint(3) unsigned NOT NULL COMMENT '出生月',
  `birthday` tinyint(3) unsigned NOT NULL COMMENT '出生日',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户信息表';

DROP TABLE IF EXISTS `hd_web`;
CREATE TABLE `hd_web` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点id',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `template_name` varchar(50) NOT NULL DEFAULT '' COMMENT '模板',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `domain` varchar(200) NOT NULL COMMENT '域名',
  `site_info` text NOT NULL COMMENT '序列化的数据',
  `is_default` tinyint(1) NOT NULL COMMENT '默认站点',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `template_name` (`template_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点设置';

DROP TABLE IF EXISTS `hd_web_article`;
CREATE TABLE `hd_web_article` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '回复规则编号',
  `iscommend` tinyint(1) unsigned NOT NULL COMMENT '推荐',
  `ishot` tinyint(1) unsigned NOT NULL COMMENT '头条',
  `template_name` varchar(50) NOT NULL DEFAULT '' COMMENT '模板',
  `title` varchar(145) NOT NULL COMMENT '标题',
  `category_cid` int(10) unsigned NOT NULL COMMENT '栏目编号',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` mediumtext NOT NULL,
  `source` varchar(45) NOT NULL COMMENT '来源',
  `author` varchar(45) NOT NULL COMMENT '作者',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `linkurl` varchar(145) NOT NULL COMMENT '外部链接地址',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `click` mediumint(8) unsigned NOT NULL COMMENT '点击数',
  `thumb` varchar(300) NOT NULL COMMENT '缩略图',
  `web_id` int(10) NOT NULL COMMENT '站点编号',
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`),
  KEY `rid` (`rid`),
  KEY `category_cid` (`category_cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='站点文章';

DROP TABLE IF EXISTS `hd_web_category`;
CREATE TABLE `hd_web_category` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '栏目标题',
  `pid` int(10) unsigned NOT NULL COMMENT '父级编号',
  `orderby` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `icontype` tinyint(1) NOT NULL COMMENT '1 图标 2 图片',
  `description` varchar(255) NOT NULL COMMENT '栏目描述',
  `template_name` varchar(50) NOT NULL DEFAULT '' COMMENT '模板',
  `linkurl` varchar(300) NOT NULL COMMENT '外部链接',
  `ishomepage` tinyint(1) unsigned NOT NULL COMMENT '封面栏目',
  `css` varchar(500) NOT NULL COMMENT 'css样式',
  `isnav` tinyint(1) NOT NULL COMMENT '导航栏目',
  `web_id` int(11) NOT NULL COMMENT '选择添加到站点首页导航时的站点编号，只对微站首页导航有效',
  PRIMARY KEY (`cid`),
  KEY `siteid` (`siteid`),
  KEY `template_name` (`template_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章分类';

DROP TABLE IF EXISTS `hd_web_nav`;
CREATE TABLE `hd_web_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `web_id` int(10) unsigned NOT NULL COMMENT '微站编号',
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
  KEY `web_id` (`web_id`),
  KEY `category_cid` (`category_cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='导航';

DROP TABLE IF EXISTS `hd_web_page`;
CREATE TABLE `hd_web_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `web_id` int(10) unsigned NOT NULL COMMENT '官网编号',
  `title` varchar(150) NOT NULL COMMENT '各称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `params` longtext NOT NULL COMMENT '参数',
  `html` longtext NOT NULL COMMENT 'html页面代码 ',
  `type` tinyint(1) NOT NULL COMMENT '1 快捷导航 2专题页面 3 会员中心',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `web_id` (`web_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微官网页面(快捷导航/专题页面)';

DROP TABLE IF EXISTS `hd_web_slide`;
CREATE TABLE `hd_web_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL COMMENT '站点编号',
  `web_id` int(10) unsigned NOT NULL COMMENT '官网编号',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `displayorder` tinyint(4) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`),
  KEY `web_id` (`web_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='幻灯图片';