<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>HDCMS - 开源免费内容管理系统 - Powered by HDCMS.COM</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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
			root: "{{__ROOT__}}",
			url: "{{__URL__}}",
			siteid: "{{SITEID}}",
			module: "{{v( 'module.name' )}}",
			//用于上传等组件使用标识当前是后台用户
			user_type: 'user'
		}
	</script>
	<link href="{{__ROOT__}}/node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{__ROOT__}}/node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="{{__ROOT__}}/node_modules/hdjs/app/util.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/require.js"></script>
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
					<if value="q('session.system.login')=='hdcms'">
						<li>
							<a href="?s=system/site/lists">
								<i class="fa fa-reply-all"></i> 返回系统
							</a>
						</li>
					</if>
					<foreach from="$LINKS['menus']" value="$m">
						<li class="top_menu">
							<a href="javascript:;"
							   onclick="hdMenus.system(this)"
							   url="{{$m['url']}}&menuid={{$m['id']}}"
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
					<if value="q('session.system.login')=='hdcms'">
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
					</if>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
							<i class="fa fa-w fa-user"></i>
							{{v('user.info.username')}}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="?s=system/user/myPassword">我的帐号</a></li>
							<if value="q('session.system.login')=='hdcms'">
								<li><a href="?s=system/manage/menu">系统选项</a></li>
							</if>
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
								<li class="list-group-item">
									<if value="$g['append_url']">
										<a class="pull-right append_url"
										   onclick="hdMenus.system(this)"
										   url="{{$g['append_url']}}&menuid={{$g['id']}}"
										   menuid="{{$g['id']}}"
										   mark="{{$g['mark']}}">
											<i class="fa fa-plus"></i>
										</a>
									</if>
									<a href="javascript:;"
									   onclick="hdMenus.system(this)"
									   url="{{$g['url']}}&menuid={{$g['id']}}"
									   menuid="{{$g['id']}}"
									   mark="{{$g['mark']}}">{{$g['title']}}
									</a>
								</li>
							</foreach>
						</ul>
					</foreach>
				</foreach>
				<!----------返回模块列表 start------------>
				<if value="$LINKS['module']">
					<div class="panel-heading hide module_back module_action" mark="package">
						<h4 class="panel-title">系统功能</h4>
						<a class="panel-collapse" data-toggle="collapse" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_action" aria-expanded="true" mark="package">
						<li class="list-group-item">
							<a href="javascript:;"
							   onclick="hdMenus.system(this)"
							   url="?s=site/entry/home"
							   menuid="{{$LINKS['module']['module']['name']}}lists"
							   mark="package">
								<i class="fa fa-reply-all"></i> 返回模块列表
							</a>
						</li>
						<li class="list-group-item">
							<a href="javascript:;"
							   onclick="hdMenus.system(this)"
							   url="?s=site/entry/module&m={{$LINKS['module']['module']['name']}}&mark=package"
							   menuid="{{$LINKS['module']['module']['name']}}"
							   mark="package">
								<i class="fa fa-desktop"></i> {{$LINKS['module']['module']['title']}}
							</a>
						</li>
					</ul>
				</if>

				<foreach from="$LINKS['module']['access']" key="$title" value="$t">
					<div class="panel-heading hide module_back module_action" mark="package">
						<h4 class="panel-title">{{$title}}</h4>
						<a class="panel-collapse" data-toggle="collapse" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_action" aria-expanded="true" mark="package">
						<foreach from="$t" value="$m">
						<li class="list-group-item">
							<a href="javascript:;"
							   onclick="hdMenus.system(this)"
							   url="{{$m['url']}}"
							   menuid="{{$m['identifying']}}"
							   module="{{$LINKS['module']['name']}}"
							   mark="package">
								<i class="{{$m['ico']}}"></i> {{$m['title']}}
							</a>
						</li>
						</foreach>
					</ul>
				</foreach>
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
							<li class="list-group-item">
								<a href="javascript:;"
								   onclick="hdMenus.system(this)"
								   mark="package"
								   url="?s=site/entry/module&m={{$g['name']}}"
								   module="{{$g['title']}}"
								   menuid="{{$g['name']}}">
									{{$g['title']}}
								</a>
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
						<a href="?s=site/entry/home&mark=package">
							&nbsp;&nbsp;<i class="fa fa-cogs"></i> 扩展模块
						</a>
					</li>
					<li class="active">
						<a href="?s=site/entry/module&mark=package&mark=package&m={{$LINKS['module']['name']}}">{{$LINKS['module']['title']}}</a>
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
<script src="{{__ROOT__}}/resource/js/quick_navigate.js"></script>
<script>
	require(['bootstrap']);
</script>
<!--右键菜单添加到快捷导航-->
<div id="context-menu">
	<ul class="dropdown-menu" role="menu">
		<li><a tabindex="-1" href="#">添加到快捷菜单</a></li>
	</ul>
</div>
<!--右键菜单删除快捷导航-->
<div id="context-menu-del">
	<ul class="dropdown-menu" role="menu">
		<li><a tabindex="-1" href="#">删除菜单</a></li>
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