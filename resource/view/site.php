<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>HDCMS - 开源免费内容管理系统 - Powered by HDCMS.COM</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="{{__ROOT__}}/node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{__ROOT__}}/node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="{{__ROOT__}}/node_modules/hdjs/app/util.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/require.js"></script>
	<script>
		//HDJS组件需要的配置
		hdjs = {
			'base': 'node_modules/hdjs',
			'uploader': '{{u("system/component/uploader")}}',
			'filesLists': '{{u("system/component/filesLists")}}',
			'removeImage': '{{u("system/component/removeImage")}}',
		};
		window.system = {
			attachment: "{{__ROOT__}}/attachment",
			user: <?php echo json_encode( Session::get( 'user' ) );?>,
			root: "<?php echo __ROOT__;?>",
			url: "{{__URL__}}",
			siteid: <?php echo SITEID;?>,
			module: "<?php echo v( 'module.name' );?>"
		}
	</script>
	<script src="{{__ROOT__}}/node_modules/hdjs/config.js"></script>
	<script src="{{__ROOT__}}/resource/js/hdcms.js"></script>
	<link href="{{__ROOT__}}/resource/css/hdcms.css" rel="stylesheet">
	<script>
		require(['jquery'], function ($) {
			//为异步请求设置CSRF令牌
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		})
		if (navigator.appName == 'Microsoft Internet Explorer') {
			if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
				alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
			}
		}
	</script>
</head>
<body class="site">
<?php $LINKS = \Menu::get(); ?>
<div class="container-fluid admin-top">
	<!--导航-->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<ul class="nav navbar-nav">
					<li>
						<a href="?s=system/site/lists">
							<i class="fa fa-reply-all"></i> 返回系统
						</a>
					</li>
					<foreach from="$LINKS['menus']" value="$m">
						<li class="top_menu">
							<a href="javascript:;"
							   onclick="hdMenus.system(this)"
							   url="{{$m['url']}}"
							   menuid="{{$m['id']}}"
							   mark="{{$m['mark']}}">
								<i class="'fa-w {{$m['icon']}}"></i> {{$m['title']}}
							</a>
						</li>
					</foreach>
				</ul>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
						   style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "
						   aria-expanded="false">
							<i class="fa fa-group"></i> {{v('site.info.name')}} <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="?s=system/site/edit&siteid={{SITEID}}"><i class="fa fa-weixin fa-fw"></i>
									编辑当前账号资料
								</a>
							</li>
							<li><a href="?s=system/site/lists"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
							<i class="fa fa-w fa-user"></i>
							{{v('user.info.username')}}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="?s=system/user/myPassword">我的帐号</a></li>
							<li><a href="?s=system/manage/menu">系统选项</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="?s=system/entry/quit">退出</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<!--导航end-->
</div>
<!--主体-->
<div class="container-fluid admin_menu">
	<div class="row">
		<div class="col-xs-12 col-sm-3 col-lg-2 left-menu">
			<div class="search-menu">
				<input class="form-control input-lg" type="text" placeholder="输入菜单名称可快速查找"
				       onkeyup="hdMenus.search(this)">
			</div>
			<!--扩展模块动作 start-->
			<div class="btn-group hide module_action_type">
				<button type="button" class="btn btn-default default"
				        onclick="hdMenus.changeModuleActionType('default');">默认
				</button>
				<button type="button" class="btn btn-default system"
				        onclick="hdMenus.changeModuleActionType('system');">系统
				</button>
				<button type="button" class="btn btn-default group" onclick="hdMenus.changeModuleActionType('group');">
					组合
				</button>
			</div>
			<!--扩展模块动作 end-->
			<div class="panel panel-default">
				<!--系统菜单-->
				<foreach from="$LINKS['menus']" value="$m">
					<foreach from="$m['_data']" value="$d">
						<div class="panel-heading hide" mark="{{$m['mark']}}">
							<h4 class="panel-title">{{$d['title']}}</h4>
							<a class="panel-collapse" data-toggle="collapse" href="javascript:;" aria-expanded="true">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in hide" mark="{{$m['mark']}}">
							<foreach from="$d['_data']" value="$g">
								<li class="list-group-item" dataHref="{{$g['url']}}"
								    onclick="hdMenus.system(this)"
								    url="{{$g['url']}}"
								    menuid="{{$g['id']}}"
								    mark="{{$g['mark']}}">
									<if value="$g['append_url']">
										<a class="pull-right"
										   onclick="hdMenus.system(this)"
										   url="{{$g['append_url']}}"
										   menuid="{{$g['id']}}"
										   mark="{{$g['mark']}}">
											<i class="fa fa-plus"></i>
										</a>
									</if>
									{{$g['title']}}
								</li>
							</foreach>
						</ul>
					</foreach>
				</foreach>
				<!--系统菜单 end-->
				<!----------返回模块列表 start------------>
				<if value="$LINKS['module']">
					<div class="panel-heading hide module_back module_action" mark="package">
						<h4 class="panel-title">系统功能</h4>
						<a class="panel-collapse" data-toggle="collapse" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_action" aria-expanded="true" mark="package">
						<li class="list-group-item"
						    onclick="hdMenus.system(this)"
						    url="?s=site/entry/home"
						    menuid="{{$LINKS['module']['name']}}lists"
						    mark="package">
							<i class="fa fa-reply-all"></i> 返回模块列表
						</li>
						<li class="list-group-item"
						    onclick="hdMenus.system(this)"
						    url="?s=site/entry/home&m={{$LINKS['module']['name']}}&mark=package"
						    menuid="{{$LINKS['module']['name']}}"
						    mark="package">
							<i class="fa fa-reply-all"></i> {{$LINKS['module']['title']}}
						</li>
					</ul>
				</if>
				<!----------返回模块列表 end------------>
				<!------------------------模块菜单 start------------------------>
				<if value="!empty($LINKS['module']['rule'])||!empty($LINKS['module']['setting'])">
					<div class="panel-heading hide module_action" mark="package">
						<h4 class="panel-title">回复规则</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#reply_rule" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_action" aria-expanded="true" mark="package">
						<if value="$LINKS['module']['rule']">
							<li class="list-group-item"
							    onclick="hdMenus.system(this)"
							    url="?s=site/reply/lists&m={{$LINKS['module']['name']}}"
							    menuid="rule"
							    module="{{$LINKS['module']['title']}}"
							    mark="package">
								<i class="fa fa-rss"></i> 回复规则列表
							</li>
						</if>
						<if value="$LINKS['module']['setting']">
							<li class="list-group-item"
							    onclick="hdMenus.system(this)"
							    url="?s=site/module/setting&m={{$LINKS['module']['name']}}"
							    menuid="setting"
							    module="{{$LINKS['module']['title']}}"
							    mark="package">
								<i class="fa fa-cog"></i> 参数设置
							</li>
						</if>
					</ul>
				</if>
				<if value="!empty($LINKS['module']['budings']['home'])||!empty($LINKS['module']['budings']['profile'])||!empty($LINKS['module']['budings']['member'])">
					<div class="panel-heading hide module_action" mark="package">
						<h4 class="panel-title">导航菜单</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#module_nav" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_action" aria-expanded="true" mark="package">
						<if value="!empty($LINKS['module']['budings']['home'])">
							<li class="list-group-item"
							    onclick="hdMenus.system(this)"
							    url="?s=site/nav/lists&entry=home&m={{$LINKS['module']['name']}}"
							    menuid="home"
							    module="{{$LINKS['module']['title']}}"
							    mark="package">
								<i class="fa fa-home"></i> 微站首页导航
							</li>
						</if>
						<if value="!empty($LINKS['module']['budings']['profile'])">
							<li class="list-group-item"
							    onclick="hdMenus.system(this)"
							    url="?s=site/nav/lists&entry=profile&m={{$LINKS['module']['name']}}"
							    menuid="profile"
							    module="{{$LINKS['module']['title']}}"
							    mark="package">
								<i class="fa fa-github"></i> 移动端会员中心导航
							</li>
						</if>
						<if value="!empty($LINKS['module']['budings']['member'])">
							<li class="list-group-item"
							    onclick="hdMenus.system(this)"
							    url="?s=site/nav/lists&entry=member&m={{$LINKS['module']['name']}}"
							    menuid="member"
							    module="{{$LINKS['module']['title']}}"
							    mark="package">
								<i class="fa fa-renren"></i> 桌面个人中心导航
							</li>
						</if>
					</ul>
				</if>
				<if value="$LINKS['module']['budings']['cover']">
					<div class="panel-heading hide module_action" mark="package">
						<h4 class="panel-title">封面入口</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#module_home" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_action" aria-expanded="true" mark="package">
						<foreach from="$LINKS['module']['budings']['cover']" value="$f">
							<li class="list-group-item"
							    onclick="hdMenus.system(this)"
							    url="?s=site/module/cover&m={{$LINKS['module']['name']}}&bid={{$f['bid']}}"
							    menuid="cover{{$f['bid']}}"
							    module="{{$LINKS['module']['title']}}"
							    mark="package">
								<i class="fa fa-comments"></i> {{$f['title']}}
							</li>
						</foreach>
					</ul>
				</if>
				<if value="$LINKS['module']['budings']['business']">
					<foreach from="$LINKS['module']['budings']['business']" value="$f">
						<div class="panel-heading hide module_action" mark="package">
							<h4 class="panel-title">{{$f['title']}}</h4>
							<a class="panel-collapse" data-toggle="collapse" href="#module_business"
							   aria-expanded="true">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in hide module_action" aria-expanded="true" mark="package">
							<foreach from="$f['do']" value="$d">
								<li class="list-group-item"
								    onclick="hdMenus.system(this)"
								    url="?s=site/module/business&m={{$LINKS['module']['name']}}&bid={{$f['bid']}}"
								    menuid="business{{$f['bid']}}"
								    module="{{$LINKS['module']['title']}}"
								    mark="package">
									<i class="fa fa-server"></i> {{$d['title']}}
								</li>
							</foreach>
						</ul>
					</foreach>
				</if>
				<!------------------------模块菜单 end------------------------>
				<!--模块列表-->
				<foreach from="$LINKS['moduleLists']" key="$t" value="$d">
					<div class="panel-heading hide" mark="package">
						<h4 class="panel-title">{{$t}}</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#moudus{{$d['mid']}}">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide" mark="package">
						<foreach from="$d" value="$g">
							<li class="list-group-item"
							    onclick="hdMenus.system(this)"
							    mark="package" ,
							    url="?s=site/entry/module&m={{$g['name']}}"
							    module="{{$g['title']}}"
							    menuid="{{$g['name']}}">
								{{$g['title']}}
							</li>
						</foreach>
					</ul>
				</foreach>
				<!--模块列表 end-->
			</div>
		</div>
		<div class="col-xs-12 col-sm-9 col-lg-10">
			<!--有模块管理时显示的面包屑导航-->
			<if value="v('module.title') && v('module.is_system')==0">
				<ol class="breadcrumb" style="background-color: #f9f9f9;padding:8px 0;margin-bottom:10px;">
					<li>
						<a href="?s=site/entry/home&p=package&menuid=21">
							&nbsp;&nbsp;<i class="fa fa-cogs"></i> 扩展模块管理
						</a>
					</li>
					<li class="active">
						<a href="?s=site/module/home&m={{$LINKS['module']['name']}}">{{$LINKS['module']['title']}}模块</a>
					</li>
					<if value="$module_action_name">
						<li class="active">
							{{$module_action_name}}
						</li>
					</if>
				</ol>
			</if>
			<blade name="content"/>
		</div>
	</div>
</div>
<div class="master-footer">
	<a href="http://www.houdunwang.com">猎人训练</a>
	<a href="http://www.hdphp.com">开源框架</a>
	<a href="http://bbs.houdunwang.com">后盾论坛</a>
	<br>
	Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
</div>
<script src="{{__ROOT__}}/resource/js/menu.js"></script>
<script src="{{__ROOT__}}/resource/js/quick_menu.js"></script>
<script>
	require(['bootstrap']);
</script>
<if value="!empty($errors)">
	<script>
		//错误消息提示
		require(['util'], function (util) {
			var obj = util.modal({
				title: '友情提示',//标题
				content: '<div><i class="pull-left fa fa-4x fa-info-circle"></i>' +
				'<div class="pull-left"><?php echo implode( '<br/>', $errors );?></div>' +
				'<div class="clearfix"></div></div>',//内容
				footer: '<button type="button" class="btn btn-default confirm" data-dismiss="modal">关闭</button>',//底部
				width: 600,//宽度
				class: 'alert alert-info',
				events: {}
			});
			//显示模态框
			obj.modal('show');
		});
	</script>
</if>
<div id="context-menu">
	<ul class="dropdown-menu" role="menu">
		<li><a tabindex="-1" href="#">添加到快捷菜单</a></li>
	</ul>
</div>
<!--底部快捷菜单导航-->
<?php $QUICKMENU = \Menu::getQuickMenu(); ?>
<if value="$QUICKMENU['status']">
	<div class="quick_navigate">
		<div class="btn-group">
		<span class="btn btn-success btn-sm">
			快捷导航
		</span>
			<foreach from="$QUICKMENU['system']" value="$v">
				<a class="btn btn-default btn-sm" href="{{$v['url']}}">
					{{$v['title']}}
				</a>
			</foreach>
			<foreach from="$QUICKMENU['module']" value="$v">
				<div class="btn-group dropup">
					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
						{{$v['title']}} <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<foreach from="$v['action']" value="$a">
							<li><a href="{{$a['url']}}">{{$a['title']}}</a></li>
						</foreach>
					</ul>
				</div>
			</foreach>
		</div>
		<i class="fa fa-times-circle-o pull-right fa-2x close_quick_menu"></i>
	</div>
</if>
</body>
</html>