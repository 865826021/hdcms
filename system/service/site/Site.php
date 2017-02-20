<?php namespace system\service\site;

use module\article\model\WebModel;
use system\model\MemberFields;
use system\model\MemberGroup;
use system\model\SiteSetting;
use system\service\Common;
use system\model\Site as SiteModel;

/**
 * 服务功能类
 * Class Site
 * @package system\service\site
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Site extends Common {
	/**
	 * 系统启动时执行的站点信息初始化
	 */
	public function siteInitialize() {
		if ( $siteid = Request::get( 'siteid' ) ) {
			if ( ! Db::table( 'site' )->where( 'siteid', $siteid )->get() ) {
				message( '你访问的站点不存在', 'back', 'error' );
			}
		}
		/**
		 * 站点编号
		 * 如果GET中存在siteid使用,否则使用SESSION会话中的siteid
		 */
		$siteId = Request::get( 'siteid', Session::get( 'siteid' ), 'intval' );
		if ( $siteId ) {
			if ( Db::table( 'site' )->find( $siteId ) ) {
				define( 'SITEID', $siteId );
				Session::set( 'siteid', $siteId );
				$this->loadSite( $siteId );
			} else {
				message( '你访问的站点不存在', 'back', 'error' );
			}
		}
	}

	/**
	 * 加载当前请求的站点缓存
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool|void
	 */
	public function loadSite( $siteId ) {
		//缓存存在时不获取
		if ( v( 'site' ) || empty( $siteId ) ) {
			return;
		}

		//站点信息
		v( 'site.info', d( "site:" . $siteId ) );
		//站点设置
		v( 'site.setting', d( "setting:" . $siteId ) );
		//微信帐号
		v( 'site.wechat', d( "wechat:" . $siteId ) );
		//加载模块
		v( 'site.modules', d( "modules:" . $siteId ) );
		//设置微信配置
		$config = [
			"token"          => v( 'site.wechat.token' ),
			"encodingaeskey" => v( 'site.wechat.encodingaeskey' ),
			"appid"          => v( 'site.wechat.appid' ),
			"appsecret"      => v( 'site.wechat.appsecret' ),
			"mch_id"         => v( 'site.setting.pay.wechat.mch_id' ),
			"key"            => v( 'site.setting.pay.wechat.key' ),
			"apiclient_cert" => v( 'site.setting.pay.wechat.apiclient_cert' ),
			"apiclient_key"  => v( 'site.setting.pay.wechat.apiclient_key' ),
			"rootca"         => v( 'site.setting.pay.wechat.rootca' ),
			"back_url"       => ''
		];
		//设置微信通信数据配置
		c( 'wechat', array_merge( c( 'wechat' ), $config ) );
		//设置邮箱配置
		c( 'mail', v( 'site.setting.smtp' ) );
		//会员中心默认风格
		define( '__UCENTER_TEMPLATE__', v( 'site.info.ucenter_template' ) );

		return true;
	}

	/**
	 * 删除站点
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool
	 */
	public function remove( $siteId ) {
		/**
		 * 删除所有包含siteid的表
		 * 因为siteid在系统中是站点编号
		 */
		$tables = \Schema::getAllTableInfo();
		foreach ( $tables['table'] as $name => $info ) {
			$table = str_replace( c( 'database.prefix' ), '', $name );
			//表中存在siteid字段时操作这个表
			if ( \Schema::fieldExists( 'siteid', $table ) ) {
				Db::table( $table )->where( 'siteid', $siteId )->delete();
			}
		}
		//删除缓存
		$keys = [ 'access', 'setting', 'wechat', 'site', 'modules', 'module_binding' ];
		foreach ( $keys as $key ) {
			d( "{$key}:{$siteId}", '[del]' );
		}

		return true;
	}

	/**
	 * 站点是否存在
	 *
	 * @param $siteId
	 *
	 * @return bool
	 */
	public function has( $siteId ) {
		return $this->where( 'siteid', $siteId )->get() ? true : false;
	}

	/**
	 * 新建站点时初始化站点的默认数据
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return bool
	 */
	public function InitializationSiteTableData( $siteId ) {
		/*
		|--------------------------------------------------------------------------
		| 站点设置
		|--------------------------------------------------------------------------
		*/
		$SiteSetting                = new SiteSetting();
		$SiteSetting['siteid']      = $siteId;
		$SiteSetting['quickmenu']   = 1;
		$SiteSetting['creditnames'] = [
			'credit1' => [ 'title' => '积分', 'status' => 1 ],
			'credit2' => [ 'title' => '余额', 'status' => 1 ],
			'credit3' => [ 'title' => '', 'status' => 0 ],
			'credit4' => [ 'title' => '', 'status' => 0 ],
			'credit5' => [ 'title' => '', 'status' => 0 ],
		];
		//注册设置
		$SiteSetting['register'] = [
			'focusreg' => 0,
			'item'     => 2
		];
		//积分策略
		$SiteSetting['creditbehaviors'] = [
			'activity' => 'credit1',
			'currency' => 'credit2'
		];
		$SiteSetting->save();

		/*
		|--------------------------------------------------------------------------
		| 站点会员组设置
		|--------------------------------------------------------------------------
		*/
		$MemberGroup              = new MemberGroup();
		$MemberGroup['siteid']    = $siteId;
		$MemberGroup['title']     = '会员';
		$MemberGroup['isdefault'] = 1;
		$MemberGroup['is_system'] = 1;
		$MemberGroup->save();

		/*
		|--------------------------------------------------------------------------
		| 创建用户字段表数据
		|--------------------------------------------------------------------------
		*/
		$memberField = new MemberFields();
		$memberField->where( 'siteid', $siteId )->delete();
		$profile_fields = Db::table( 'profile_fields' )->get();
		foreach ( $profile_fields as $f ) {
			$d['siteid']  = $siteId;
			$d['field']   = $f['field'];
			$d['title']   = $f['title'];
			$d['orderby'] = $f['orderby'];
			$d['status']  = $f['status'];
			$memberField->insert( $d );
		}

		/*
		|--------------------------------------------------------------------------
		| 初始快捷菜单
		|--------------------------------------------------------------------------
		*/
		$data['siteid'] = $siteId;
		$data['data']   = '{"status":1,"system":[],"module":[]}';
		$data['uid']    = v( 'user.info.uid' );
		Db::table( 'site_quickmenu' )->insert( $data );

		/*
		|--------------------------------------------------------------------------
		| 初始文章系统表
		|--------------------------------------------------------------------------
		*/
		$model = new WebModel();
		$model->insert( [ 'siteid' => $siteId, 'model_title' => '普通文章', 'model_name' => 'news', 'is_system' => 1 ] );
		$model->createModelTable( 'news', $siteId );

		/*
		|--------------------------------------------------------------------------
		| 设置文章系统域名为安装域名
		|--------------------------------------------------------------------------
		*/
		Db::table( 'module_domain' )->insert( ['siteid'=>$siteId, 'domain'=>$_SERVER['HTTP_HOST'], 'module' => 'article' ] );
		//创建栏目表
		$sql=<<<str
INSERT INTO `hd_web_category` (`cid`, `siteid`, `mid`, `catname`, `pid`, `orderby`, `status`, `description`, `linkurl`, `ishomepage`, `index_tpl`, `category_tpl`, `content_tpl`, `html_category`, `html_content`)
VALUES
	(7,{$siteId},10,'IT资讯',0,0,1,'','',1,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(8,{$siteId},10,'游戏之家',0,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(9,{$siteId},10,'安卓之家',0,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(10,{$siteId},10,'iPhone之家',0,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(11,{$siteId},10,'数码之家',0,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(12,{$siteId},10,'Win10之家',0,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(13,{$siteId},10,'辣品',0,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(14,{$siteId},10,'国内IT资讯',7,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html'),
	(15,{$siteId},10,'国外IT资讯',7,0,1,'','',0,'article_index.html','article_list.html','article.html','article{siteid}-{cid}-{page}.html','article{siteid}-{aid}-{cid}-{mid}.html');
str;
		Db::execute($sql);

		//创建默认文章内容
		$sql=<<<str
INSERT INTO `hd_web_content_news{$siteId}` (`aid`, `siteid`, `mid`, `cid`, `keyword`, `iscommend`, `ishot`, `title`, `click`, `thumb`, `description`, `content`, `source`, `author`, `orderby`, `linkurl`, `createtime`, `长文本`, `template`)
VALUES
	(10,{$siteId},10,7,'',1,0,'vivo Funtouch OS 3.0 Lite公测版开放更新：老机型福音',0,'attachment/59091487238280.jpg','日前vivo宣布旗下Funtouch OS 3.0 Lite公测版开放在线更新，首批更新机型为X6S、X6S Plus、X7、X7 Plus、Xplay5A、Xplay5S，如果你在此前申请过公测可以在“系统设置——系统升级”菜单中检查更新。','<p>日前vivo宣布旗下Funtouch OS 3.0 Lite公测版开放在线更新，首批更新机型为X6S、X6S Plus、X7、X7 Plus、Xplay5A、Xplay5S，如果你在此前申请过公测可以在“系统设置——系统升级”菜单中检查更新。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_171426_981.jpg\" alt=\"vivo Funtouch OS 3.0 Lite公测版开放更新：老机型福音\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_171426_981.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>更新日志<br style=\"-webkit-user-select: text !important;\"/></p><p>以下是3.0 Lite公测版的更新日志，请V粉们在“系统升级”菜单中检查更新时核对新版本的更新日志，若与下面的内容一致就可以正常升级。</p><p>升级注意：</p><p>•此次升级包较大，建议在WIFI环境下进行下载，并保持电量充足</p><p>•若您修改过系统（如Root，修改系统文件等），必须要恢复官方原版系统才能升级</p><p>•请务必在升级前备份好重要数据</p><p>•此版本包含全新的视觉和一些功能设计，对部分原有操作方式也有一定调整</p><p>•更多内容了解请关注Funtouch OS官方网站：&nbsp;www.vivo.com.cn/funtouchos</p><p>系统：</p><p>部分升级内容：</p><p>•智慧引擎3.0</p><p>•全新视觉设计，内置三套全新设计的官方主题</p><p>•桌面动态图标，天气/日历/闹钟时钟图标实时变化</p><p>•短信验证码/地址/日期自动识别</p><p>•文件管理微信/QQ专有文件夹</p><p>•天气可查看14天天气数据</p><p>•相册图片马赛克，文字标注，涂鸦的编辑功能</p><p>•超大字体</p><p>•更多有趣设计等待您的体验</p><p>注意：从2.5.1/2.6升级到3.0 Lite公测版后，桌面日历挂件需要重新添加。</p><p>更新步骤：</p><p>1、升级3.0 Lite之前必须要将2.5.1/2.6版升级到最新版本（进入系统设置——系统升级菜单中升级）。</p><p>2、若您修改过系统（如Root、修改系统文件等），必须要恢复官方原版系统才能升级3.0 Lite。</p><p>3、本次更新的版本是公测版，仍属于测试版本，请在升级前备份好重要数据，以免出现不必要的损失。</p><p>4、因各地网络环境不同，不同地区的V粉可能收到推送的时间有差异，请耐心等待推送。</p><p>5、升级公测版可能存在以下问题：</p><p>-存在一些未知Bug，可能会影响一些功能的使用</p><p>-部分功能有待完善，以及因机型差异可能会有调整</p><p>-稳定性可能还有优化的空间</p><p>相关链接：<a href=\"http://bbs.vivo.com.cn/thread-2736310-1-1.html\" target=\"_blank\">点此访问</a></p><p><br/></p>','','',0,'0',1487320476,'',''),
	(11,{$siteId},10,7,'',0,0,'苹果iPhone7也可以无线充电？试试这个ZENS保护壳',0,'attachment/49021487238325.jpg','值得一提的是，ZENS保护壳目前已经通过了MFi和Qi认证，这意味着它可以和市面上的绝大多数无线充电板配合使用。此外，这款产品还内置有一个MicroUSB接口，用户可以在有需要的时候连接充电线缆。\r\n这款ZENS保护壳预计将于2月底进入市场，感兴趣的朋友可以前往ZENS的在线商城预订。','<p>目前，有不少媒体报道称苹果将会在今年的新款<a href=\"http://iphone.ithome.com/\" target=\"_blank\">iPhone8</a>中加入无线充电功能，可是，我们不必等到苹果在iPhone中加入这项功能了，因为<a href=\"http://iphone.ithome.com/\" target=\"_blank\">iPhone7</a>也可以通过ZENS保护壳进行无线充电。</p><p>据了解，ZENS是一款为iPhone 7带来无线充电功能的保护壳，在保持“苗条”的同时也可以为iPhone 7手机提供不错的保护功能，ZENS和无线充电器的捆绑价格为60美元(约合人民币412元)。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216171252_3796.jpg\" alt=\"苹果iPhone7也可以无线充电？试试这个ZENS保护壳\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216171252_3796.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>虽然苹果可能认为目前的无线充电技术还不够成熟，或者体验不够好，但是<a href=\"http://android.ithome.com/\" target=\"_blank\">Android</a>用户已经对这一充电方式非常熟悉，而iPhone 7用户也可以通过ZENS保护壳获得无线充电的功能。</p><p>值得一提的是，ZENS保护壳目前已经通过了MFi和Qi认证，这意味着它可以和市面上的绝大多数无线充电板配合使用。此外，这款产品还内置有一个MicroUSB接口，用户可以在有需要的时候连接充电线缆。</p><p>这款ZENS保护壳预计将于2月底进入市场，感兴趣的朋友可以前往ZENS的在线商城预订。</p><p><br/></p>','','',0,'0',1487238334,'',''),
	(12,{$siteId},10,7,'',0,0,'创新圆形设计/不再为空间烦恼，品胜1.8米3位3USB智能宽座插线板59.9元',0,'attachment/59781487244629.png','创新圆形设计/不再为空间烦恼，品胜1.8米3位3USB智能宽座插线板报价69.9元，限时限量10元券（领券入口），实付59.9元包邮！赠运费险，点此专享特惠。','创新圆形设计/不再为空间烦恼，品胜1.8米3位3USB智能宽座插线板报价69.9元，限时限量10元券（领券入口），实付59.9元包邮！赠运费险，点此专享特惠。\r\n\r\n选择品胜，安心充电，创意圆形设计，拒绝拥挤，3个USB接口，2.4A智能快充，防火材料，过载保护，加厚铜片，双动安全门，超级方便实用，专为智能设备设计。\r\n\r\n• 点此享受创新圆形设计/不再为空间烦恼，品胜1.8米3位3USB智能宽座插线板59.9元：领券入口 | 购买入口。\r\n• 火辣的价格、火辣的商品，更多促销优惠访问辣品（www.lapin365.com）\r\n• 搜索“辣品”可关注辣品官方微博、微信公众号账号。','','',0,'0',1487244703,'',''),
	(13,{$siteId},10,8,'',0,0,'北京研制H7N9流感全病毒灭活疫苗，获准进入临床试验',0,'attachment/8401487244731.png','北京市研制的4个H7N9流感防控药品近日取得国家食药监总局药物临床试验批件，获准进入临床试验阶段。\r\n','<p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">北京市研制的4个H7N9流感防控药品近日取得国家食药监总局药物临床试验批件，获准进入临床试验阶段。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">4个H7N9流感防控药品分别为H7N9流感病毒裂解疫苗、H7N9流感病毒裂解疫苗（佐剂）、H7N9流感全病毒灭活疫苗、H7N9流感病毒裂解疫苗（30μg/剂）。研制单位专家表示，4种疫苗的作用效果需要在临床中进一步评价。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">上述4种药品的研制是北京市做好H7N9流感防控工作的重要组成部分。在疫苗的前期研制和注册申报过程中，北京食药监局成立专门工作小组，与H7N9流感病毒疫苗研制单位建立了全天候沟通机制，并为其开辟行政审批绿色通道，采取多种有效措施，全力推进北京市H7N9流感疫苗研发与注册审批工作。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">下一步，北京食药监局将继续加强与有关单位的协调配合，加强对疫苗临床试验的监督与指导，促进H7N9流感疫苗临床试验顺利开展，在后续注册申报、审批工作上积极与上级部门沟通，力争京产H7N9流感疫苗早日申报上市使用。</p><p><br/></p>','','',0,'0',1487244739,'',''),
	(14,{$siteId},10,9,'',0,0,'格力差点做了净化器？董明珠：没有价值',0,'attachment/25511487244769.jpg','随着人们对于生活水平要求的提高，在一些经济发达的地区不少的家庭也在雾霾天使用上了空气净化器，在互联网公司方面除了小米之外，包含锤子、魅族都有打造空气净化器的计划。','<p>&nbsp;随着人们对于生活水平要求的提高，在一些经济发达的地区不少的家庭也在雾霾天使用上了空气净化器，在互联网公司方面除了小米之外，包含锤子、魅族都有打造空气净化器的计划。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_182027_692.jpg\" alt=\"格力差点做了净化器？董明珠：没有价值\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_182027_692.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>据央视报道，董明珠在央视财经《为中国实业代言》启动仪上表示：企业家的责任是要让人们生活得更美好，不以消耗资源为代价。她说：“2012年我开人大会时，有人说董明珠你的机会来了！现在雾霾，你做净化器马上就赚钱了。我想，如果我做个净化器赚钱了，是用什么样的心态做产品呢？把别人的健康作为代价来开发产品，来实现我们的利润，我认为它是没有价值的。</p><p>2012年到今天，雾霾不因为我们有了空气净化器所以没有了，而是雾霾越来越严重，所以我们要有一种责任和担当。”</p><p>从董明珠的发言当中我们不难看出其早在2012年就有考虑关于空气净化器的问题，只不过因为道义上的因素，最终并没有实现。</p><p><br/></p>','','',0,'0',1487244786,'',''),
	(15,{$siteId},10,7,'',0,0,'不只是Surface Book，微软英国Surface Pro 4入门版大涨856元',0,'attachment/42381487244805.jpg','据微软发言人向外媒TechCrunch确认，该公司的确在英国调高了Surface设备的售价，主要原因就是“应对英镑货币在市场中出现的价格变动”。微软还确认，Surface Book和Surface Pro 4价格上调仅适合普通个人消费者和未加入批量许可授权的组织。','<p>&nbsp;昨天IT之家报道，由于英镑贬值，微软确认英国版Surface Book售价大幅上涨，其中Surface Book基础版售价上涨150英镑。而现在外媒发现微软Surface Pro 4价格也迎来上调，其中基础版Surface Pro 4上涨100英镑（约合人民币856元）。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_180317_5.jpg\" alt=\"不只是Surface Book，微软英国Surface Pro 4入门版大涨856元\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_180317_5.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>根据微软发言人向外媒TechCrunch确认，该公司的确在英国调高了Surface设备的售价，主要原因就是“应对英镑货币在市场中出现的价格变动”。微软还确认，Surface Book和Surface Pro 4价格上调仅适合普通个人消费者和未加入批量许可授权的组织。</p><p>受此影响，入门版Surface Book售价从1299英镑上涨到1449英镑，而最高端的1TB Intel Core i7/16GB内存/dGPU版Surface Book售价上调为3049英镑。而最高端的1TB Intel Core i7/16GB内存版Surface Pro 4售价为2359英镑。</p><p><br/></p>','','',0,'0',1487244828,'',''),
	(16,{$siteId},10,11,'',0,0,'干货教程：如何在PD虚拟机上安装老版本苹果OS X',0,'attachment/57731487244848.jpg','现如今，苹果已经将OS X系统更名为macOS，但是仍有不少用户需要使用到老版本OS X系统的特殊兼容性。网友“沛撸酱_LPR”分享了一个在Parallels Desktop虚拟机上安装老版本OS X系统的操作办法，一起来看看吧。','<p>现如今，苹果已经将OS X系统更名为macOS，但是仍有不少用户需要使用到老版本OS X系统的特殊兼容性。网友“沛撸酱_LPR”分享了一个在Parallels Desktop虚拟机上安装老版本OS X系统的操作办法，一起来看看吧。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216180604_5236.jpg\" alt=\"干货教程：如何在PD虚拟机上安装老版本苹果OS X\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216180604_5236.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>在开始操作之前，我们需要做好以下准备工作：</p><p>- OS X系统的原版镜像，App格式、OS X Install ESD版本均可</p><p>- Parallels Desktop虚拟机</p><p>- 28G以上空间(设置虚拟机磁盘大小为24GB)</p><p>-一个管理员账户的macOS</p><p>一、安装PD</p><p>首先，我们需要安装一个Parallels Desktop虚拟机。</p><p>二、制作镜像</p><p>1.下载OS X的系统镜像。</p><p>此步骤主要是创建一个系统安装镜像，其方法类似于创建一个系统安装U盘，不同版本的OS X安装目录可能不同。下载的镜像文件大小关系为：原版压缩后的dmg &gt; App格式安装文件&gt; InstallESD.dmg &gt;完整安装U盘镜像。</p><p>如果你下载的是压缩版：</p><p>双击挂载下载好的镜像，看到镜像中的“安装OS X ---”的App。</p><p>如果你下载的是App版本：</p><p>右键点击App，点击“显示包内容”，然后点开文件夹Contents - Shared Support，找到InstallESD.dmg。</p><p>如果你下载的是InstallESD.dmg版本：</p><p>挂载InstallESD.dmg</p><p>打开“应用程序-实用工具”里的“终端”，使用终端命令显示隐藏文件，这一操作会终止Finder的所有操作，我们需要确保当前没有复制文件等操作。</p><p>defaults write com.apple.finder AppleShowAllFiles TRUE</p><p>killall Finder</p><p>在刚刚挂载的InstallESD.dmg文件中应该会出现一个BaseSystem.dmg文件和Package文件夹。</p><p>2.接下来是制作镜像。</p><p>打开“应用程序-实用工具”里的“磁盘工具”。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216180604_2350.jpg\" alt=\"干货教程：如何在PD虚拟机上安装老版本苹果OS X\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216180604_2350.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>新建一个空白镜像，大小为8000MB(大小必须大于6000MB，且为读/写磁盘镜像)，设置如下图：</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216180605_9436.jpg\" alt=\"干货教程：如何在PD虚拟机上安装老版本苹果OS X\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216180605_9436.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>系统应该会自动挂载镜像，如果没有自动挂载，则需要双击挂载。</p><p>在“磁盘工具”中选择新挂载的镜像，对其进行恢复选项。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216180605_1751.jpg\" alt=\"干货教程：如何在PD虚拟机上安装老版本苹果OS X\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216180605_1751.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>点击“映像”按钮，选择刚刚找到的BaseSystem.dmg并进行恢复，等待完成。</p><p>点开镜像，找到System - Installation文件夹，删除Package。</p><p>将InstallESD.dmg中的Package文件夹复制到Installation文件夹中。</p><p>最后回到磁盘工具，新建镜像，选择“未命名”的磁盘镜像，并制作。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216180606_3852.jpg\" alt=\"干货教程：如何在PD虚拟机上安装老版本苹果OS X\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216180606_3852.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>打开“应用程序-实用工具”里的“终端”，使用终端命令显示隐藏文件，这一操作会终止Finder的所有操作，我们需要确保当前没有复制文件等操作。</p><p>defaults write com.apple.finder AppleShowAllFiles FALSE</p><p>killall Finde</p><p>到这里，镜像制作完毕。</p><p>三、安装OS X</p><p>1.打开Parallels Desktop虚拟机，使用新建指令Command + N新建虚拟机。</p><p>2.选择从镜像安装并继续。</p><p><br/></p>','','',0,'0',1487244879,'',''),
	(17,{$siteId},10,12,'',1,0,'诺基亚拟再产17年前畅销机3310，网友：旧版还没坏',0,'attachment/50901487244914.jpg','在智能手机风行的当下，诺基亚已经成为怀旧和回忆的代名词。\r\n近日有消息传出，拥有诺基亚手机品牌独家经营权的芬兰制造商HMD Glob','<p>在智能手机风行的当下，诺基亚已经成为怀旧和回忆的代名词。</p><p>近日有消息传出，拥有诺基亚手机品牌独家经营权的芬兰制造商HMD Global Oy，将在2月底举行的2017世界移动大会（MWC）上，重新发布17年前的畅销机型“诺基亚3310”。</p><p>诺基亚3310于2000年问世，2005年停产，曾经在全球卖出1.26亿台，被称为全球最畅销的手机。超长的待机时间、“坚不可摧”的机身，以及质朴但令人沉迷的贪吃蛇游戏，都是让诺基亚3310在当年迅速走红的重要因素，而对今天的人们来说，这已经成为怀旧片段。</p><p>据外媒报道，再产的诺基亚3310将会在原版的基础上提升性能，但具体会在哪些方面改进，还没有更详细的说明。而新版诺基亚3310价格将比2000年初次发行时的129欧元大幅下降，只要59欧元（折合人民币约430元）。并且，新版的3310将仅在北美和欧洲市场销售。</p><p>英国《独立报》的报道称，这款机型将定义为可以在智能手机没电时启用的备用机型，同时以一种怀旧的姿态迎合购买者消费需求。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216180539_2432.jpg\" alt=\"诺基亚拟再产17年前畅销机3310，网友：旧版还没坏\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216180539_2432.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>▲外国网友调侃诺基亚3310手机“坚不可摧”。</p><p>据悉，除了重新推出诺基亚3310，HMD还将同期发布廉价版安卓手机诺基亚7.0 Nougat以及诺基亚5和诺基亚3。目前诺基亚已经在中国市场发布了诺基亚6系列手机，该款手机配置有5.5英寸全高清分辨率显示屏、新一代高通骁龙430处理器、4GB运行内存和64GB存储空间，并有1600万像素后置摄像头和800万像素前置摄像头。诺基亚6的发布被视为诺基亚品牌重回手机市场的标志。</p><p>HMD公司成立于2016年5月，总部设在芬兰，公司总裁和首席执行官均为原诺基亚高管。2013年9月，诺基亚宣布将江河日下的手机业务出售给微软。但没想到，微软也回天乏术，在2016年5月，将诺基亚功能手机业务又转售给了HMD公司和富士康子公司富智康。HMD拿到授权后，可以生产新一代诺基亚品牌手机和平板电脑，并拥有在未来10年内生产诺基亚品牌智能手机和平板电脑的权利。</p><p>诺基亚3310重新上市的消息传出以后，外国网友纷纷在社交网络上晒出了自己与3310的合影，掀起了一股怀旧的浪潮。</p><p>不过，人们也表现出了对3310再产不同的态度。有网友称：“就让3310成为过去式吧，没必要玷污回忆。”而也有网友表现出欢迎的态度，称上市以后会第一个去购买，还有人在推特上发布了一张用诺基亚3310撑开门缝的图片，上面附有文字：“史上最多功能的手机”。也有网友戏称，诺基亚3310再产是多此一举，“这款手机实在太耐用了，17年前的旧版还能工作。”</p><p><br/></p>','','',0,'0',1487320413,'',''),
	(18,{$siteId},10,12,'',1,0,'见证历史：《DOTA2》第30亿局比赛今日诞生',0,'attachment/42011487244959.jpg','在数据网站DOTABUFF上，DOTA2第10亿和第20亿场的比赛都已不可考，不过根据reddit论坛的发帖记录，第20亿场比赛的发生时间在2014年末。也就是说，在短短2年的时间内，全球玩家就进行了10亿次对战比赛，实在惊人。','<p>《DOTA2》诞生多年以来，已是全球最热门的竞技游戏之一，至今玩家们进行了上亿次对战。而今日，《DOTA2》历史上的第30亿局比赛正式诞生了，这是一场来自东南亚服务器的匹配对战，ID是3,000,000,000。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_180154_810.jpg\" alt=\"见证历史：《DOTA2》第30亿局比赛今日诞生\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_180154_810.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>▲DOTA2第30亿局比赛</p><p>在这场具有历史意义的比赛中，双方贡献了86次击杀，历时44分4秒，最终获胜方为天辉。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_180213_498.jpg\" alt=\"见证历史：《DOTA2》第30亿局比赛今日诞生\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_180213_498.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>▲第30亿局比赛数据图</p><p>在数据网站DOTABUFF上，DOTA2第10亿和第20亿场的比赛都已不可考，不过根据reddit论坛的发帖记录，第20亿场比赛的发生时间在2014年末。也就是说，在短短2年的时间内，全球玩家就进行了10亿次对战比赛，实在惊人。</p><p><br/></p>','','',0,'0',1487320406,'',''),
	(19,{$siteId},10,7,'',1,0,'《尼尔：机械纪元》7天倒计时：机器人大军整装待发',0,'attachment/10601487245127.jpg','','<p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">今日，小编了解到《尼尔：机械纪元》官方放出了7天倒计时的图片，本次倒计时图片主题为机器人大军来袭，整体画风偏暗黑系。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\"><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_175651_665.jpg\" alt=\"《尼尔：机械纪元》7天倒计时：机器人大军整装待发\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_175651_665.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">通过图片来看，一群黑色的机器人大军出现在画面上，似乎在暗示玩家将要同这些机器人进行对战，官方表示希望玩家在通关之后再来重新审视一下游戏，目前尚不知道官方是何用意。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\"><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_175657_7.jpg\" alt=\"《尼尔：机械纪元》7天倒计时：机器人大军整装待发\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_175657_7.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">《尼尔：机械纪元》是由SE和白金工作室联合开发的一款动作角色扮演游戏。故事发生在和《尼尔》相同的世界，并将有一个全新的故事剧情，但和前作几乎没有关联。</p><p><br/></p>','','',0,'0',1487320427,'',''),
	(20,{$siteId},10,13,'',0,0,'尼龙/磨砂/PU多款可选，TB ONE男女百搭时尚休闲双肩包39元',0,'attachment/47551487245157.jpg','火辣的价格、火辣的商品，更多促销优惠访问辣品（www.lapin365.com）\r\n• 搜索“辣品”可关注辣品官方微博、微信公众号账号。','<p>TB ONE男女百搭时尚休闲双肩包天猫旗舰店报价59元，限时限量20元券（<a href=\"https://shop.m.taobao.com/shop/coupon.htm?seller_id=1990847375&activity_id=9e4c2b9b3d2f4256a57cfebc79795338\" target=\"_blank\" rel=\"nofollow\">领券入口</a>），实付39元包邮！赠运费险，<a href=\"https://s.click.taobao.com/t?e=m%3D2%26s%3D24Qb48hPrHFw4vFB6t2Z2ueEDrYVVa64yK8Cckff7TVRAdhuF14FMY%2Fgv6487luAMMgx22UI05YRiNCXxE2bgjE6C7TDxXxWQqM2CRkH4CnvYSY7y0K7CdA5ko9KoCn678FqzS29vh5nPjZ5WWqolN%2FWWjML5JdM90WyHry0om0ExGSdSYZVFun%2BUl%2B3Be9QW9%2BqZC6wTixRLBgaW5udaw%3D%3D\" target=\"_blank\">点此专享特惠</a>。</p><p><img src=\"http://img.alicdn.com/imgextra/i3/1990847375/TB2glcwXqi5V1BjSspnXXa.3XXa_!!1990847375.jpg_430x430q90.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/></p><p>精选优质面料，精致细腻做工，型格至上，经典百搭，既大方又实用，多款多色可选。</p><p><img src=\"https://img.alicdn.com/imgextra/i3/1990847375/TB2tbVXbiGO.eBjSZFjXXcU9FXa_!!1990847375.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/><img src=\"https://img.alicdn.com/imgextra/i3/1990847375/TB2krKZX4Qa61Bjy0FhXXaalFXa_!!1990847375.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/><img src=\"https://img.alicdn.com/imgextra/i3/1990847375/TB2Cyu1X4UX61BjSszeXXbpQpXa_!!1990847375.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/><img src=\"https://img.alicdn.com/imgextra/i3/1990847375/TB2ekYQbl0kpuFjSsziXXa.oVXa_!!1990847375.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/></p><p>•&nbsp;点此享受尼龙/磨砂/PU多款可选，TB ONE男女百搭时尚休闲双肩包39元：<a href=\"https://shop.m.taobao.com/shop/coupon.htm?seller_id=1990847375&activity_id=9e4c2b9b3d2f4256a57cfebc79795338\" target=\"_blank\">领券入口</a>&nbsp;|&nbsp;<a href=\"https://s.click.taobao.com/t?e=m%3D2%26s%3D24Qb48hPrHFw4vFB6t2Z2ueEDrYVVa64yK8Cckff7TVRAdhuF14FMY%2Fgv6487luAMMgx22UI05YRiNCXxE2bgjE6C7TDxXxWQqM2CRkH4CnvYSY7y0K7CdA5ko9KoCn678FqzS29vh5nPjZ5WWqolN%2FWWjML5JdM90WyHry0om0ExGSdSYZVFun%2BUl%2B3Be9QW9%2BqZC6wTixRLBgaW5udaw%3D%3D\" target=\"_blank\">购买入口</a>。</p><p>•&nbsp;火辣的价格、火辣的商品，更多促销优惠访问辣品（<a href=\"http://www.lapin365.com/\" target=\"_blank\">www.lapin365.com</a>）<br style=\"-webkit-user-select: text !important;\"/></p><p>• 搜索“辣品”可关注辣品官方微博、微信公众号账号。</p><p><br/></p>','','',0,'0',1487245176,'',''),
	(21,{$siteId},10,14,'',0,0,'CEO萨提亚·纳德拉上任3年，微软最大的改变是文化',0,'attachment/78881487249301.jpg','“成长型心态”这词来自斯坦福大学心理学家Carol Dweck的《Mindset》一书。纳德拉在接受媒体采访时曾提到，这本书里讨论了一个概念：如果有两个人，一个无所不学，一个无所不知，从长期来看，无所不学者总是会打败无所不知者。','<p>萨提亚·纳德拉上任微软3周年最好的献礼莫过于微软最新一季度的财报：智能云成为最亮眼的部分，而且微软市值也从2000年后首次重新回到了5000亿美元。业界认为纳德拉已经成功将微软重构成为了一家基于互联网的企业服务供应商。而相比这个，对于员工来说，感受到最大的变化居然是文化。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216163140_8281.jpg\" alt=\"CEO萨提亚·纳德拉上任3年，微软最大的改变是文化\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216163140_8281.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>网易科技受邀参观了微软西雅图总部，在与当地员工交流中不断被提及到的两个词就是：“一个微软”和成长型心态。一位在微软工作了十几年的员工表示，经历了比尔盖茨、史蒂夫鲍尔默和萨提亚纳德拉三任CEO，每任CEO的风格都大不相同，但纳德拉让微软十几万员工在短短3年内，实现了自上而下心态上的转变，可以说是一个强有力的执行者。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216163116_9491.jpg\" alt=\"CEO萨提亚·纳德拉上任3年，微软最大的改变是文化\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216163116_9491.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>一个微软</p><p>在微软最新财年的财报中，微软营收240.90亿美元，净利润为52.00亿美元，与去年同期的50.18亿美元相比增长4%。其中最为亮眼的就是微软“智能云”业务的增长：该业务营收增至69亿美元，而Azure营收涨幅高达93%，翻了近一番。这得益于Satya Nadella（萨提亚纳德拉）上任时提出的“移动为先，云为先”发展战略，使其云业务成为处于全球领先地位的亚马逊的挑战者。而且，财报之后，微软市值也从2000年后首次重新回到了5000亿美元。</p><p>业界评价纳德拉已经成功将微软重构成为了一家基于互联网的企业服务供应商。短短3年时间，微软实现如此之快的转型，与纳德拉大刀阔斧的改革不无相关，这一改革更多的是体现在微软文化上的变革。</p><p>微软企业传播团队总经理Tim O’Brien透露，纳德拉担任CEO之后微软有一个非常大的转变：从之前的强调单个组织和个人到现在强调整体和团队，也就是“一个微软”的概念。</p><p>其实，“一个微软”的概念在纳德拉接任鲍尔默之前的一年，鲍尔默就提出了这个概念。鲍尔默表示员工要围绕着一个公司、一个战略的核心团结在一起，而不是每个部门都有各自的战略；要把所有产品都整合为一个整体，而不是分割成一组“岛屿”。</p><p>但这个概念一直只是在内部强调，从未对外推广。但萨提亚上任之后将这方面的推广进行了加强，并且身体力行。</p><p>“想象一下你可能是学校最好的学生，但到了微软你会发现所有同事都一样，甚至可能比你更聪明。个性化会让大家处于不断竞争的状态，而不是加强合作。但很明显整体效能远远大于小我。萨提亚在微软20多年看到了太多这样的状态。因此，身体力行，让微软实现了自上而下的转变。”Tim表示。</p><p>“一个微软”最明显的体现就是在绩效考核上：不再强调个人成就，而是强调团队合作。“之前微软恨不得用一个标尺来衡量做的工作，但现在更看重的是员工与主管之前的沟通，考核除了自己做了什么，还要看帮助别人做了什么，和别人一起做了什么，这三个考核维度。”微软员工表示。</p><p>据了解，萨提亚成为微软CEO之后，微软员工考核KPI中有一项就是“你对别人工作的贡献率”。</p><p>“一个微软”口号到底能够在微软起到多大的影响？Tim表示让微软的效率更高，创造的价值也更高。“文化转型起到了非常巨大的作用，之前很多项目并行开发，相互之前没有合作和沟通，但现在更多是协同合作。比如Windows就是一个非常好的例子，之前嵌入式、PC端和移动端的Windows各自为营，而现在则可以实现不同平台之前的全面协同合作。”</p><p>成长型心态</p><p>除了“一个微软”的概念，萨提亚给微软员工带来的另一个改变就是“成长型心态”。</p><p>“成长型心态”这词来自斯坦福大学心理学家Carol Dweck的《Mindset》一书。纳德拉在接受媒体采访时曾提到，这本书里讨论了一个概念：如果有两个人，一个无所不学，一个无所不知，从长期来看，无所不学者总是会打败无所不知者。纳德拉认为这一道理适合微软的每一位员工。他会在每天下班之后问自己：“我思维太封闭了吗？我有对增长表现出正确的态度了吗？”</p><p>因此，纳德拉一直强调微软不要有的“领导者”心态，而是要做一个“追赶者”。</p><p>“很多公司会陷入这样的怪圈：会逃避风险，规避风险，但这时候还期待创新的出现。其实，规避风险的心态会让公司花更多时间在负面效果上。但萨提亚提出的成长型心态就是要强调学习，只要去学习了，其他都是次要的。”Tim表示。</p><p>不过，Tim也坦言如此庞大的机构，在这场文化变革中一定会有积极推动者，也会有希望能够保持现状的人。“希望能够保持现状的员工开始可能会离开，这是一种普遍的状态。很难预测这两种员工的百分比。但在微软的整个转变中，只有非常少的员工离开。而且，有些之前离开去谷歌、苹果的员工在看到了微软的这个变化之后，又选择了回来。”Tim提到。</p><p><br/></p>','','',0,'0',1487401835,'',''),
	(22,{$siteId},10,15,'',1,0,'《速度与激情8》国内或已过审，飞车家族分崩离析',0,'attachment/69501487249349.jpg','','<p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">据微博电影守望者余泳爆料，《速度与激情8》内地已经过审，目前尚未公布上映日期。北美的上映日期为4月14日，内地不知道是否能够同步上映。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">在中文版海报上，范·迪塞尔扮演的唐老大与道恩·强森扮演的霍布斯警官相背而站，大大的标语写着“家庭分崩离析”，看来在《速度与激情8》中，原本关系默契的亲人好友之间会爆发剧烈的矛盾。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\"><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216164702_4800.jpg\" alt=\"《速度与激情8》国内或已过审，飞车家族分崩离析\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216164702_4800.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\">《速度与激情8》故事将延续上一部剧情，多米尼克与莱蒂共度蜜月，布莱恩与米娅退出了赛车界，众人的生活渐趋平淡，而查理兹·塞隆饰演的神秘女子Cipher的出现却打乱了所有平静，她引诱多米尼克走上犯罪道路，令整个队伍卷入信任与背叛的危机，生死患难的情义面临瓦解崩溃，前所未有的灾难考验着飞车家族。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\"><span style=\"font-weight: 700; -webkit-user-select: text !important;\">早先公布的剧照：</span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\"><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216164702_1914.jpg\" alt=\"《速度与激情8》国内或已过审，飞车家族分崩离析\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216164702_1914.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216164703_9001.jpg\" alt=\"《速度与激情8》国内或已过审，飞车家族分崩离析\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216164703_9001.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 10px 0px; color: rgb(66, 66, 66); font-size: 1pc; line-height: 30px; font-family: &#39;Microsoft Yahei&#39;; text-align: justify; white-space: normal; -webkit-user-select: text !important; background-color: rgb(255, 255, 255);\"><span style=\"font-weight: 700; -webkit-user-select: text !important;\">《速度与激情8》中文预告：</span></p><p><br/></p>','','',0,'0',1487404249,'',''),
	(23,{$siteId},10,11,'',1,0,'不喜欢防窥膜？这款苹果手机壳能抽出防窥盖',0,'attachment/72401487249554.jpg','','<p>Shadeze是一款内置防窥盖的<a href=\"http://iphone.ithome.com/\" target=\"_blank\">iPhone</a>手机壳，将隐藏在手机壳背部的盖子拉伸出来就可以挡住屏幕上方，设计非常新颖。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216163633_7059.jpg\" alt=\"不喜欢防窥膜？这款苹果手机壳能抽出防窥盖\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216163633_7059.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>现如今，人人都是低头族，地铁上、公交上、咖啡厅里甚至走路时，都在看手机。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216163650_7116.jpg\" alt=\"不喜欢防窥膜？这款苹果手机壳能抽出防窥盖\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216163650_7116.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>而随着智能手机的泛用性越来越广，已经成为人们的信息中心，装载着大量的个人隐私和信息。</p><p><img src=\"http://mat1.gtimg.com/digi/ppp/2/1/1/ke1.gif\" alt=\"不喜欢防窥膜？那就来个能抽出防窥盖的手机壳\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/></p><p>这就带来一个问题，如果手机被有心人偷看，那么个人信息就有可能泄露。</p><p>仔细想想，手机可能被偷窥的场景还是很多的，比如地铁上、电梯里等等。</p><p><img src=\"http://mat1.gtimg.com/digi/ppp/2/1/1/ke2.gif\" alt=\"不喜欢防窥膜？那就来个能抽出防窥盖的手机壳\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/></p><p>有人会说：那贴张防窥膜呗，防窥膜虽好，但对手机使用还是有一些影响的，因为它会缩小手机屏幕的可视角度。</p><p><img src=\"http://mat1.gtimg.com/digi/ppp/2/1/1/ke3.gif\" alt=\"不喜欢防窥膜？那就来个能抽出防窥盖的手机壳\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/></p><p>于是，有人发明了一种“笨办法”，就是物理防窥，Shadeze就是这样一款手机壳，它的设计理念其实很简单，通过在手机壳背部内置一个可拉伸出来的防窥盖，需要时抽出来盖在屏幕上方，旁边的人就看不到屏幕了。</p><p><img src=\"http://mat1.gtimg.com/digi/ppp/2/1/1/ke4.gif\" alt=\"不喜欢防窥膜？那就来个能抽出防窥盖的手机壳\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/></p><p>虽然感觉上没有防窥膜方便，但Shadeze胜在灵活，毕竟不是所有时候都需要防窥的，另外Shadeze还有一个好处，就是能够挡住阳光，尤其是在一些光线复杂的环境下，自拍效果也许会更好一些，当然，Shadeze也是一款很漂亮的手机壳。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216163651_4417.jpg\" alt=\"不喜欢防窥膜？这款苹果手机壳能抽出防窥盖\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216163651_4417.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>色彩非常年轻化，不足则是仅支持<a href=\"http://iphone.ithome.com/\" target=\"_blank\">iPhone6</a>/<a href=\"http://iphone.ithome.com/\" target=\"_blank\">iPhone6s</a>/<a href=\"http://iphone.ithome.com/\" target=\"_blank\">iPhone7</a>及<a href=\"http://iphone.ithome.com/\" target=\"_blank\">iPhone7 Plus</a>机型，暂时没有推出Android版本的计划，至于价格，最低15澳元（约合人民币80元）也是比较划算的。</p><p><br/></p>','','',0,'0',1487320396,'',''),
	(24,{$siteId},10,15,'',0,0,'《愤怒的小鸟：岛屿》正式公布：不搞破坏，玩起大富翁',0,'attachment/68891487249655.png','HN Studio629除了正式公布《愤怒的小鸟：岛屿》外，还确认了游戏的测试时间，本次的封测预定从3月2日至11日，期间将开启城镇经营、迷宫探索、玩家社交等内容。目前，测试预约活动已于官网开始，感兴趣的玩家请留意后续报道。\r\n','<p>《愤怒的小鸟》又出新作了，不过这是Rovio授权韩国游戏开发商NHN Studio629制作的一款社交游戏Angry Birds Islands《愤怒的小鸟：岛屿》。这是继《愤怒的小鸟：大富翁》之后的又一韩厂的新作，在这之前还有《愤怒的小鸟：进化》、《愤怒的小鸟：爆破》等非正统作。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_163946_772.png\" alt=\"《愤怒的小鸟：岛屿》正式公布：不搞破坏，玩起大富翁\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_163946_772.png\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>据了解，《愤怒的小鸟：岛屿》是以《愤怒的小鸟》系列游戏中的人气角色制作的一款休闲游戏，玩家将在未知的岛屿上建立自己的城镇，通过采集、建造、生产等各种方式来经营自己的城镇，还可与其它玩家共同合作。此外，迷宫探索、冒险战斗这些玩法可以增加资源的来源，而且还可以自定义自己的城镇，收集各式饰品来装扮外观，让自己的城镇变得独一无二。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_163950_407.png\" alt=\"《愤怒的小鸟：岛屿》正式公布：不搞破坏，玩起大富翁\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_163950_407.png\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>NHN Studio629除了正式公布《愤怒的小鸟：岛屿》外，还确认了游戏的测试时间，本次的封测预定从3月2日至11日，期间将开启城镇经营、迷宫探索、玩家社交等内容。目前，测试预约活动已于官网开始，感兴趣的玩家请留意后续报道。</p><p><br/></p>','','',0,'0',1487401844,'',''),
	(25,{$siteId},10,13,'',0,0,'入门级手机市场利润太少，HTC表示将放弃',0,'attachment/82881487249687.jpg','HTC的这一行动无不让人联想到了索尼，三年前索尼也放弃了入门市场，力求在高端市场追上苹果三星。另外值得一提的是，此前张嘉临还表示HTC今年将减少新机发布数量，更多是注重新功能的开发。','<p>入门级智能手机在新兴市场很受欢迎，百十来块的价格足以影响消费者的购买决定。当然，在这些对价格尤为明显的市场，竞争压力也不小，有些时候是非搞价格战不可。现在HTC已经表示将退出入门级手机市场。</p><p>在日前HTC Q4财报会议上，HTC智能手机和联网设备总裁张嘉临表示HTC将放弃入门级智能手机业务。这名高管表示，入门级手机市场环境竞争压力巨大，而且缺乏盈利能力。在过去发布的机型中，除了旗舰机型之外，HTC的其它产品线都同时兼顾了高端到低端。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216163523_5007.jpg\" alt=\"入门级手机市场利润太少，HTC表示将放弃\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216163523_5007.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>HTC的这一行动无不让人联想到了索尼，三年前索尼也放弃了入门市场，力求在高端市场追上苹果三星。另外值得一提的是，此前张嘉临还表示HTC今年将减少新机发布数量，更多是注重新功能的开发。</p><p>根据此前HTC公布的Q4财报，其净亏损达1亿美元，但HTC Vive VR头盔去年总营收达3.3558亿美元，市场份额第一。</p><p><br/></p>','','',0,'0',1487249697,'',''),
	(26,{$siteId},10,7,'',0,0,'迷你口袋3D影院，VR SHINECON千幻魔镜',0,'attachment/30041487249724.jpg','VR SHINECON千幻魔镜5代虚拟现实VR眼镜天猫旗舰店报价29.9元起，限时限量20元券（领券入口），实付9.9元包邮，点此专享特惠。\r\n迷你口袋3D影院，VR SHINECON千幻魔镜5代虚拟现实VR眼镜9.9元起\r\n观影神器，海量片源，360度全景震撼3D，随时随地想看就看！\r\n','<p>VR SHINECON千幻魔镜5代虚拟现实VR眼镜天猫旗舰店报价29.9元起，限时限量20元券（<a href=\"https://shop.m.taobao.com/shop/coupon.htm?sellerId=2980689728&activityId=1f7959bb88ca4ebeaa6e5fcae9c0d526\" target=\"_blank\" rel=\"nofollow\">领券入口</a>），实付9.9元包邮，<a href=\"https://s.click.taobao.com/t?e=m%3D2%26s%3DOFDBLiz56cdw4vFB6t2Z2ueEDrYVVa64yK8Cckff7TVRAdhuF14FMQVgRxrX67CCJ1gyddu7kN8RiNCXxE2bgjE6C7TDxXxWQqM2CRkH4CnvYSY7y0K7CdA5ko9KoCn678FqzS29vh5nPjZ5WWqolN%2FWWjML5JdM90WyHry0om3NDXwxw9FMIJNhdNwncj6mOFKpziNh%2BMkdA80niSzcPA%3D%3D\" target=\"_blank\">点此专享特惠</a>。</p><p><img class=\"lazy\" src=\"http://img.lapin365.com/productpictures/2016/12/12/20161212_202659_6565.jpg\" alt=\"迷你口袋3D影院，VR SHINECON千幻魔镜5代虚拟现实VR眼镜9.9元起\" data-original=\"//img.lapin365.com/productpictures/2016/12/12/20161212_202659_6565.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>观影神器，海量片源，360度全景震撼3D，随时随地想看就看！</p><p><img src=\"https://img.alicdn.com/imgextra/i1/2980689728/TB2KXGlXNBmpuFjSZFDXXXD8pXa_!!2980689728.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/><img src=\"https://img.alicdn.com/imgextra/i4/2980689728/TB22ymkXItnpuFjSZFvXXbcTpXa_!!2980689728.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/><img src=\"https://img.alicdn.com/imgextra/i3/2980689728/TB2SVimdMOI.eBjSszhXXbHvFXa_!!2980689728.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px;\"/></p><p><br/></p>','','',0,'0',1487249726,'',''),
	(27,{$siteId},10,9,'',0,0,'高二男生决心戒掉网络直播：看太多影响身体',0,'attachment/91291487249801.jpg','生的儿子并不知道这是违法行为，看到报道后就明白了，甚至还有一点后怕。董先生称，他本来还有点抵触，现在下决心不看这些东西了。\r\n对于这些地下涉黄直播平台，多位家长表示深恶痛绝，希望国家有关部门能够形成合力，尽快将该不良现象打击下去，以免危害孩子的健康成长。','<p>据南方都市报报道，董先生的儿子是一名高中生，明年就要高考。前段时间他发现，儿子近段时间学习成绩下降，整天没精打采，脸色也不好，偶然的机会下，他发现儿子通过手机在看涉黄直播。</p><p>经过检查，董先生发现儿子加入了很多微信群、QQ群，这些群经常发一些视频和平台更新的链接，儿子还买了一个聚合软件，40块钱一个，可以免费进房间。</p><p><img class=\"lazy\" src=\"http://img.ithome.com/newsuploadfiles/2017/2/20170216_163055_539.jpg\" alt=\"高二男生决心戒掉网络直播：看太多影响身体\" data-original=\"//img.ithome.com/newsuploadfiles/2017/2/20170216_163055_539.jpg\" style=\"-webkit-user-select: text !important; border: 0px; vertical-align: bottom; abc: 728px; display: inline;\"/></p><p>据悉，所谓的“聚合软件”，就是将市面上出现的色情直播集中在一个软件，只要有一个账号，就可以登录所有的色情直播。</p><p>由于涉世未深，董先生的儿子并不知道这是违法行为，看到报道后就明白了，甚至还有一点后怕。董先生称，他本来还有点抵触，现在下决心不看这些东西了。</p><p>对于这些地下涉黄直播平台，多位家长表示深恶痛绝，希望国家有关部门能够形成合力，尽快将该不良现象打击下去，以免危害孩子的健康成长。</p><p><br/></p>','','',0,'0',1487249804,'',''),
	(31,{$siteId},10,14,'',0,0,'dsfsfd',0,'','dsffds','','','',0,'0',1487555598,'','');
str;
		Db::execute($sql);
		return true;
	}

	/**
	 * 获取用户管理的所有站点信息
	 *
	 * @param int $uid 用户编号
	 *
	 * @return array 站点列表
	 */
	public function getUserAllSite( $uid ) {
		return SiteModel::join( 'site_user', 'site.siteid', '=', 'site_user.siteid' )
		                ->where( 'site_user.uid', $uid )->get();
	}

	/**
	 * 更新站点数据缓存
	 *
	 * @param int $siteId 网站编号
	 *
	 * @return bool
	 */
	public function updateCache( $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;
		//站点微信信息缓存
		$wechat         = Db::table( 'site_wechat' )->where( 'siteid', $siteId )->first();
		$data['wechat'] = $wechat ?: [ ];
		//站点信息缓存
		$site         = Db::table( 'site' )->where( 'siteid', $siteId )->first();
		$data['site'] = $site ?: [ ];
		//站点设置缓存
		$setting                     = Db::table( 'site_setting' )->where( 'siteid', $siteId )->first();
		$setting                     = $setting ?: [ ];
		$setting ['creditnames']     = json_decode( $setting['creditnames'], true );
		$setting ['creditbehaviors'] = json_decode( $setting['creditbehaviors'], true );
		$setting ['register']        = json_decode( $setting['register'], true );
		$setting ['smtp']            = json_decode( $setting['smtp'], true );
		$setting ['pay']             = json_decode( $setting['pay'], true );
		$setting ['quickmenu']       = $setting['quickmenu'];
		$data['setting']             = $setting;
		//站点模块
		$data['modules'] = \Module::getSiteAllModules( $siteId, false );
		foreach ( $data as $key => $value ) {
			d( "{$key}:{$siteId}", $value );
		}

		return true;
	}

	/**
	 * 更新所有站点缓存
	 * @return bool
	 */
	public function updateAllCache() {
		foreach ( (array) SiteModel::lists( 'siteid' ) as $siteid ) {
			$this->updateCache( $siteid );
		}

		return true;
	}

	/**
	 * 获取站点默认组
	 * @return mixed
	 */
	public function getDefaultGroup() {
		return Db::table( 'member_group' )->where( 'siteid', v( 'site.siteid' ) )->where( 'isdefault', 1 )->pluck( 'id' );
	}

	/**
	 * 获取站点所有组
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteGroups( $siteid = null ) {
		$siteid = $siteid ?: SITEID;

		return Db::table( 'member_group' )->where( 'siteid', $siteid )->get() ?: [ ];
	}

	/**
	 * 初始化站点的微信
	 *
	 * @param $siteid
	 *
	 * @return 微信表新增主键编号
	 */
	public function initSiteWeChat( $siteid ) {
		$data = [
			'siteid'     => $siteid,
			'wename'     => '',
			'account'    => '',
			'original'   => '',
			'level'      => 1,
			'appid'      => '',
			'appsecret'  => '',
			'qrcode'     => '',
			'icon'       => '',
			'is_connect' => 0,
		];

		return $this->insertGetId( $data );
	}
}