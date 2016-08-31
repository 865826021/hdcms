INSERT INTO `hd_user` (`uid`, `groupid`, `username`, `password`, `security`, `status`, `regtime`, `regip`, `lasttime`, `lastip`, `starttime`, `endtime`, `qq`, `mobile`, `email`, `mobile_valid`, `email_valid`, `remark`)
VALUES
	(1,0,'admin','3df440190ae5c0397d8dd7dd99e59047','e489322ee5',1,1465771582,'123.119.83.235',1470217059,'124.205.39.82',1465771582,1465771582,'','','',0,0,'系统管理员帐号');

INSERT INTO `hd_config` (`id`, `site`, `upload`, `register`)
VALUES (1,'{"is_open":1,"enable_code":0,"close_message":"网站维护中,请稍候访问","upload":{"size":20000,"type":"jpg,jpeg,gif,png,zip,rar,doc,txt,pem"}}','','{\"is_open\":1,\"audit\":\"1\",\"enable_code\":0,\"groupid\":1}');

INSERT INTO `hd_member_group` (`id`, `siteid`, `title`, `credit`, `rank`, `isdefault`, `is_system`)
VALUES (1,1,'普通会员',0,0,1,1);

INSERT INTO `hd_cloud` (`id`, `uid`, `username`, `webname`, `AppSecret`, `versionCode`, `releaseCode`, `createtime`, `status`)
VALUES
	(1,1,'','','','','',1470656410,0);

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
	(85,78,'微站快捷导航','article_quick_menu','?a=quickmenu/post&t=site&m=article','','fa fa-cubes',0,1,0,'article'),
	(86,82,'微信菜单','menus_lists','?a=site/lists&t=site&m=button','','fa fa-cubes',0,1,0,'platform'),
	(87,1,'微信素材','','','','fa fa-cubes',0,1,0,'platform'),
	(88,87,'素材&群发','material','?a=site/image&t=site&m=material','','fa fa-cubes',0,1,0,'platform');

INSERT INTO `hd_modules` (`mid`, `name`, `industry`, `title`, `version`, `resume`, `detail`, `author`, `url`, `is_system`, `subscribes`, `processors`, `setting`, `rule`, `permissions`, `thumb`, `cover`, `locality`, `releaseCode`)
VALUES
	(1,'basic','business','基本文字回复','1.0','和您进行简单对话','一问一答得简单对话. 当访客的对话语句中包含指定关键字, 或对话语句完全等于特定关键字, 将回复文字或链接给用户','后盾','http://open.hdcms.com','1','','a:1:{i:0;s:4:\"text\";}',0,0,'','thumb.jpg','cover.jpg',1,''),
	(2,'news','business','基本混合图文回复','1.0','为你提供生动的图文资讯','一问一答得简单对话. 当访客的对话语句中包含指定关键字, 或对话语句完全等于特定关键字, 将回复文字或链接给用户','后盾','http://open.hdcms.com','1','','a:1:{i:0;s:4:\"text\";}',0,0,'','thumb.jpg','cover.jpg',1,''),
	(3,'article','business','文章系统','1.0','发布文章与会员中心管理','支持桌面、移动、微信三网的文章系统，同时具有移动、桌面会员中心管理功能','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg',1,''),
	(4,'setting','business','网站配置','1.0','网站运行整体配置','网站运行配置项，如支付、邮箱、登录等等的全局配置项管理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg',1,''),
	(5,'member','business','会员粉丝','1.0','会员管理','会员与会员组管理，如会员字段，粉丝管理、会员卡设置','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg',1,''),
	(6,'special','business','微信默认消息','1.0','微信默认消息','系统默认消息与关注微信消息处理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg',1,''),
	(7,'ticket','business','卡券管理','1.0','会员卡券管理','会员优惠券、代金券、实物券管理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg',1,''),
	(8,'cover','business','封面回复','1.0','封面消息回复','用来处理模块的封面消息','后盾','http://open.hdcms.com','1','','a:1:{i:0;s:4:\"text\";}',0,0,'','thumb.png','cover.jpg',1,''),
	(9,'uc','business','会员中心','1.0','会员中心的管理操作','会员信息的管理，包括收货地址、个人资料、会员卡券等管理','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.png','cover.jpg',1,''),
	(10,'button','business','微信菜单','1.0','微信菜单管理','用于添加微信菜单，更新菜单后需要取消关注再关注或等微信更新缓存后有效','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg',1,''),
	(11,'material','business','微信素材','1.0','微信素材','公众号经常有需要用到一些临时性的多媒体素材的场景，例如在使用接口特别是发送消息时，对多媒体文件、多媒体消息的获取和调用等操作','后盾','http://open.hdcms.com','1','','',0,0,'','thumb.jpg','cover.jpg',1,'');

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

INSERT INTO `hd_template` (`tid`, `name`, `title`, `version`, `resume`, `author`, `url`, `industry`, `position`, `is_system`, `thumb`, `is_default`, `locality`)
VALUES
	(1,'default','默认模板','1.9','HDCMS 默认模板','后盾人','http://open.hdcms.com','other',10,1,'thumb.jpg',0,1);

INSERT INTO `hd_user_group` (`id`, `name`, `maxsite`, `daylimit`, `package`)
VALUES
(1,'体验用户组',3,16,'a:2:{i:0;s:2:\"-1\";i:1;s:2:\"12\";}');