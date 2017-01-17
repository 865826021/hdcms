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
	<script src="{{__ROOT__}}/resource/js/menu.js"></script>
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
<?php $_LINKS_ = \Menu::get(); ?>
<div class="container-fluid admin-top">
	<!--导航-->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<ul class="nav navbar-nav">
					<li>
						<a href="?s=system/site/lists"><i class="fa fa-reply-all"></i> 返回系统</a>
					</li>
					<foreach from="$_LINKS_['menus']" value="$m">
						<li class="top_menu" id="top_menu_{{$m['id']}}">
							<a href="javascript:;" dataHref="{{$m['url']}}" menuid="{{$m['id']}}">
								<i class="'fa-w {{$m['icon']}}"></i> {{$m['title']}}
							</a>
						</li>
					</foreach>
					<li>
						<a href="http://doc.hdcms.com" target="_blank"><i class="fa fa-w fa-file-code-o"></i> 在线文档</a>
					</li>
					<li>
						<a href="http://bbs.houdunwang.com" target="_blank"><i class="fa fa-w fa-forumbee"></i> 论坛讨论</a>
					</li>
				</ul>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
						   style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "
						   aria-expanded="false">
							<i class="fa fa-group"></i> {{v('site.info.name')}} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="?s=system/site/edit&siteid={{SITEID}}"><i class="fa fa-weixin fa-fw"></i>
									编辑当前账号资料</a>
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
							<li role="separator" class="divider"></li>
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
			<div id="search-menu">
				<input class="form-control input-lg" type="text" placeholder="输入菜单名称可快速查找" onkeyup="searchMenu(this)">
			</div>
			<!--扩展模块动作 start-->
			<div class="btn-group hide menu_action_type">
				<button type="button" class="btn btn-default" onclick="changeModuleActionType(1);">默认</button>
				<button type="button" class="btn btn-default" onclick="changeModuleActionType(2);">系统</button>
				<button type="button" class="btn btn-default" onclick="changeModuleActionType(3);">组合</button>
			</div>
			<!--扩展模块动作 end-->
			<div class="panel panel-default" id="menus">
				<!--系统菜单-->
				<foreach from="$_LINKS_['menus']" value="$m">
					<foreach from="$m['_data']" value="$d">
						<div class="panel-heading hide" menuid="{{$m['id']}}">
							<h4 class="panel-title">{{$d['title']}}</h4>
							<a class="panel-collapse" data-toggle="collapse" href="javascript:;" aria-expanded="true">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in hide" menuid="{{$m['id']}}">
							<foreach from="$d['_data']" value="$g">
								<li menuid="{{$m['id']}}" class="list-group-item" dataHref="{{$g['url']}}">
									<if value="$g['append_url']">
										<a class="pull-right" dataHref="{{$g['append_url']}}" menuid="{{$m['id']}}"><i
												class="fa fa-plus"></i></a>
									</if>
									{{$g['title']}}
								</li>
							</foreach>
						</ul>
					</foreach>
				</foreach>
				<!--系统菜单 end-->
				<!----------返回模块列表 start------------>
				<if value="$_LINKS_['module']">
					<div class="panel-heading hide module_back">
						<h4 class="panel-title">模块列表</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#reply_rule" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_back" aria-expanded="true">
						<li class="list-group-item" dataHref="?s=site/entry/home&p=package&menuid=21">
							<i class="fa fa-reply-all"></i> 返回模块列表
						</li>
						<li class="list-group-item"
						    dataHref="?s=site/module/home&m=hd&m={{$_LINKS_['module']['name']}}">
							<i class="fa fa-reply-all"></i> {{$_LINKS_['module']['title']}}
						</li>
					</ul>
				</if>
				<!----------返回模块列表 end------------>
				<!------------------------模块菜单 start------------------------>
				<if value="!empty($_LINKS_['module']['rule'])||!empty($_LINKS_['module']['setting'])">
					<div class="panel-heading hide module_active">
						<h4 class="panel-title">{{$_LINKS_['module']['title']}}回复规则</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#reply_rule" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_active" aria-expanded="true">
						<if value="$_LINKS_['module']['rule']">
							<li class="list-group-item" dataHref="?s=site/reply/lists&m={{$_LINKS_['module']['name']}}">
								<i class="fa fa-comments"></i> 回复规则列表
							</li>
						</if>
						<if value="$_LINKS_['module']['setting']">
							<li class="list-group-item"
							    dataHref="?s=site/module/setting&m={{$_LINKS_['module']['name']}}">
								<i class="fa fa-cog"></i> 参数设置
							</li>
						</if>
					</ul>
				</if>
				<if value="!empty($_LINKS_['module']['budings']['home'])||!empty($_LINKS_['module']['budings']['profile'])||!empty($_LINKS_['module']['budings']['member'])">
					<div class="panel-heading hide module_active">
						<h4 class="panel-title">{{$_LINKS_['module']['title']}}导航菜单</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#module_nav" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
				</if>
				<ul class="list-group menus collapse in hide module_active" aria-expanded="true">
					<if value="!empty($_LINKS_['module']['budings']['home'])">
						<li class="list-group-item"
						    dataHref="?s=site/nav/lists&entry=home&m={{$_LINKS_['module']['name']}}">
							<i class="fa fa-home"></i> 微站首页导航
						</li>
					</if>
					<if value="!empty($_LINKS_['module']['budings']['profile'])">
						<li class="list-group-item"
						    dataHref="?s=site/nav/lists&entry=profile&m={{$_LINKS_['module']['name']}}">
							<i class="fa fa-user"></i> 手机个人中心导航
						</li>
					</if>
					<if value="!empty($_LINKS_['module']['budings']['member'])">
						<li class="list-group-item"
						    dataHref="?s=site/nav/lists&entry=member&m={{$_LINKS_['module']['name']}}">
							<i class="fa fa-user"></i> 桌面个人中心导航
						</li>
					</if>
				</ul>
				<if value="$_LINKS_['module']['budings']['cover']">
					<div class="panel-heading hide module_active">
						<h4 class="panel-title">{{$_LINKS_['module']['title']}}封面入口</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#module_home" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_active" aria-expanded="true">
						<foreach from="$_LINKS_['module']['budings']['cover']" value="$f">
							<li class="list-group-item"
							    dataHref="?s=site/module/cover&m={{$_LINKS_['module']['name']}}&bid={{$f['bid']}}">
								<i class="fa fa-puzzle-piece"></i> {{$f['title']}}
							</li>
						</foreach>
					</ul>
				</if>
				<if value="$_LINKS_['module']['budings']['business']">
					<div class="panel-heading hide module_active">
						<h4 class="panel-title">{{$_LINKS_['module']['title']}}业务菜单</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#module_business" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_active" aria-expanded="true">
						<foreach from="$_LINKS_['module']['budings']['business']" value="$f">
							<li class="list-group-item"
							    dataHref="?s=site/module/business&m={{$_LINKS_['module']['name']}}&bid={{$f['bid']}}">
								<i class="fa fa-puzzle-piece"></i> {{$f['title']}}
							</li>
						</foreach>
					</ul>
				</if>
				<!------------------------模块菜单 end------------------------>
				<!--模块列表-->
				<foreach from="$_LINKS_['moduleLists']" key="$t" value="$d">
					<div class="panel-heading hide module_lists">
						<h4 class="panel-title">{{$t}}</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#moudus{{$d['mid']}}">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in hide module_lists">
						<foreach from="$d" value="$g">
							<li class="list-group-item" data-type="module_menu" menuid="21"
							    dataHref="?s=site/module/home&m={{$g['name']}}">
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
					<li><a href="?s=site/entry/home&p=package&menuid=21"><i class="fa fa-cogs"></i> 扩展模块管理</a></li>
					<li class="active">
						<a href="?s=site/module/home&m={{$_LINKS_['module']['name']}}">{{$_LINKS_['module']['title']}}模块</a>
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
	<a href="http://www.houdunwang.com">高端培训</a>
	<a href="http://www.hdphp.com">开源框架</a>
	<a href="http://bbs.houdunwang.com">后盾论坛</a>
	<br>
	Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
</div>
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
</body>
</html>