<?php if ( ! defined( 'APP_PATH' ) ) {
	exit;
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>HDCMS - 开源免费内容管理系统 - Powered by HDCMS.COM</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="{{__ROOT__}}/resource/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{__ROOT__}}/resource/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="{{__ROOT__}}/resource/hdjs/js/jquery.min.js"></script>
	<script src="{{__ROOT__}}/resource/hdjs/app/util.js"></script>
	<script src="{{__ROOT__}}/resource/hdjs/require.js"></script>
	<script src="{{__ROOT__}}/resource/hdjs/app/config.js"></script>
	<script src="{{__ROOT__}}/resource/js/common.js"></script>
	<link href="{{__ROOT__}}/resource/css/site.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
	<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script>
		window.sys = {
			attachment: 'attachment',
			uid: <?php echo Session::get( 'user.uid' );?>,
			siteid: <?php echo Session::get( 'siteid' );?>,
			root: "<?php echo __ROOT__;?>"
		}
	</script>
	<script>
		if (navigator.appName == 'Microsoft Internet Explorer') {
			if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
				alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
			}
		}
	</script>
</head>
<body>
<div class="container-fluid admin-top">
	<!--导航-->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<ul class="nav navbar-nav">
					<li>
						<a href="?s=system/site/lists"><i class="fa fa-reply-all"></i> 返回系统</a>
					</li>
					<foreach from="$_site_menu_" value="$m">
						<li class="top_menu" id="top_menu_{{$m['id']}}">
							<a href="javascript:;" dataHref="{{$m['url']}}" menuid="{{$m['id']}}">
								<i class="'fa-w {{$m['icon']}}"></i> {{$m['title']}}
							</a>
						</li>
					</foreach>
					<li>
						<a href="http://www.kancloud.cn/houdunwang/hdphp/163276" target="_blank"><i class="fa fa-w fa-file-code-o"></i> 在线文档</a>
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
							<i class="fa fa-group"></i> {{v('site.name')}} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="?s=system/site/edit&siteid={{$_SESSION['siteid']}}"><i class="fa fa-weixin fa-fw"></i> 编辑当前账号资料</a>
							</li>
							<li><a href="?s=system/site/lists"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
							<i class="fa fa-w fa-user"></i>
							<?php echo Session::get( 'user.username' ) ?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="?s=system/user/myPassword">我的帐号</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="?s=system/manage/menu">系统选项</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="?system/entry/quit">退出</a></li>
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
				<input class="form-control input-lg" type="text" placeholder="输入菜单名称可快速查找">
			</div>
			<div class="panel panel-default">
				<!--系统菜单-->
				<foreach from="$_site_menu_" value="$m">
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
										<a class="pull-right" dataHref="{{$g['append_url']}}" menuid="{{$m['id']}}"><i class="fa fa-plus"></i></a>
									</if>
									{{$g['title']}}
								</li>
							</foreach>
						</ul>
					</foreach>
				</foreach>
				<!--系统菜单 end-->

				<!--扩展模块动作-->
				<div class="btn-group ext-menu">
					<button type="button" class="btn {{$_COOKIE['ext_menu_type']==1?'btn-primary':'btn-default'}}" data-id="1">默认1</button>
					<button type="button" class="btn {{$_COOKIE['ext_menu_type']==2?'btn-primary':'btn-default'}}" data-id="2">系统</button>
					<button type="button" class="btn {{$_COOKIE['ext_menu_type']==3?'btn-primary':'btn-default'}}" data-id="3">组合</button>
				</div>
				<style>
					.ext-menu { width : 100%; border-bottom : solid 1px #dddddd; }

					.ext-menu .btn {
						border        : none;
						border-radius : 0px;
						width         : 33.6%;
						height        : 35px;
					}

					.ext-menu .btn:nth-child(2), .ext-menu .btn:nth-child(3) {
						border-width : 0px 1px 0px 1px !important;
						border-style : solid;
						border-color : #dddddd;
					}
				</style>
					<div class="panel-heading ">
						<h4 class="panel-title">模块列表</h4>
						<a class="panel-collapse" data-toggle="collapse" href="#reply_rule" aria-expanded="true">
							<i class="fa fa-chevron-circle-down"></i>
						</a>
					</div>
					<ul class="list-group menus collapse in " aria-expanded="true">
						<li class="list-group-item" data-href="?s=package/home/welcome">
							<i class="fa fa-reply-all"></i> 返回模块列表
						</li>
						<li class="list-group-item" href="?s=package/module/home&m={{$_SESSION['MODULE_ACTION']['name']}}">
							<i class="fa fa-reply-all"></i> {{$_SESSION['MODULE_ACTION']['title']}}
						</li>
					</ul>
				<if value="$_COOKIE['ext_menu_type']==1 || $_COOKIE['ext_menu_type']==3">
					<if value="!empty($_SESSION['MODULE_ACTION']['budings']['rule'])">
						<div class="panel-heading ">
							<h4 class="panel-title">{{$moduleLinks['title']}}回复规则</h4>
							<a class="panel-collapse" data-toggle="collapse" href="#reply_rule" aria-expanded="true">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in module_active" aria-expanded="true">
							<li class="list-group-item" data-href="?s=platform/reply/post&m={{$_SESSION['MODULE_ACTION']['name']}}">
								<i class="fa fa-comments"></i> 回复规则列表
							</li>
							<li class="list-group-item" data-href="?s=package/module/setting&m={{$_SESSION['MODULE_ACTION']['name']}}">
								<i class="fa fa-cog"></i> 参数设置
							</li>
						</ul>
					</if>
					<if value="!empty($_SESSION['MODULE_ACTION']['budings']['home']) || !empty($_SESSION['MODULE_ACTION']['budings']['profile'])|| !empty($moduleLinks['budings']['quick']) || !empty($moduleLinks['budings']['member'])">
						<div class="panel-heading ">
							<h4 class="panel-title">{{$moduleLinks['title']}}导航菜单</h4>
							<a class="panel-collapse" data-toggle="collapse" href="#module_nav" aria-expanded="true">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in module_active" aria-expanded="true">
							<if value="!empty($_SESSION['MODULE_ACTION']['budings']['home'])">
								<li class="list-group-item" data-href="?s=article/nav/lists&m={{$_SESSION['MODULE_ACTION']['name']}}&t=home">
									<i class="fa fa-home"></i> 微站首页导航
								</li>
							</if>
							<if value="!empty($_SESSION['MODULE_ACTION']['budings']['profile'])">
								<li class="list-group-item" data-href="?s=article/nav/lists&m={{$_SESSION['MODULE_ACTION']['name']}}&t=profile">
									<i class="fa fa-user"></i> 手机个人中心导航
								</li>
							</if>
							<if value="!empty($_SESSION['MODULE_ACTION']['budings']['member'])">
								<li class="list-group-item" data-href="?s=article/nav/lists&m={{$_SESSION['MODULE_ACTION']['name']}}&t=member">
									<i class="fa fa-user"></i> 桌面个人中心导航
								</li>
							</if>
						</ul>
					</if>
					<if value="!empty($_SESSION['MODULE_ACTION']['budings']['cover'])">
						<div class="panel-heading ">
							<h4 class="panel-title">{{$moduleLinks['title']}}封面入口</h4>
							<a class="panel-collapse" data-toggle="collapse" href="#module_home" aria-expanded="true">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in module_active" aria-expanded="true">
							<foreach from="$_SESSION['MODULE_ACTION']['budings']['cover']" value="$f">
								<li class="list-group-item" data-href="?s=package/module/cover&bid={{$f['bid']}}">
									<i class="fa fa-puzzle-piece"></i> {{$f['title']}}
								</li>
							</foreach>
						</ul>
					</if>
					<if value="!empty($_SESSION['MODULE_ACTION']['budings']['business'])">
						<div class="panel-heading ">
							<h4 class="panel-title">{{$moduleLinks['title']}}业务菜单</h4>
							<a class="panel-collapse" data-toggle="collapse" href="#module_business" aria-expanded="true">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in module_active" aria-expanded="true">
							<foreach from="$_SESSION['MODULE_ACTION']['budings']['business']" value="$f">
								<li class="list-group-item" data-href="?s=package/module/business&bid={{$f['bid']}}">
									<i class="fa fa-puzzle-piece"></i> {{$f['title']}}
								</li>
							</foreach>
						</ul>
					</if>
				</if>
				<!--扩展模块动作 end-->
				<!--模块列表-->
					<foreach from="$_site_menu_modules_" key="$t" value="$d">
						<div class="panel-heading module_menu">
							<h4 class="panel-title">{{$t}}</h4>
							<a class="panel-collapse" data-toggle="collapse" href="#moudus{{$d['mid']}}">
								<i class="fa fa-chevron-circle-down"></i>
							</a>
						</div>
						<ul class="list-group menus collapse in module_menu">
							<foreach from="$d" value="$g">
								<li class="list-group-item" data-type="module_menu" menuid="33" dataHref="?s=site/module/home&m={{$g['name']}}">
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
					<li><a href="?s=system/manage/menu"><i class="fa fa-cogs"></i> 扩展模块管理</a></li>
					<li class="active">
						<a href="?s=package/module/home&m={{$_SESSION['MODULE_ACTION']['name']}}">{{$_SESSION['MODULE_ACTION']['title']}}</a>
					</li>
					<if value="$_COOKIE['CURRENT_MODULE_ACTION']">
						<li class="active">
							{{$_COOKIE['CURRENT_MODULE_ACTION']}}
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

<script>
	//链接跳转
	$("[dataHref]").click(function (event) {
		var url = $(this).attr('dataHref');
		//记录当前点击的菜单
		sessionStorage.setItem('dataHref', url);
		sessionStorage.setItem('menuid', $(this).attr('menuid'));
		location.href = url + '&menuid=' + sessionStorage.getItem('menuid');
		//阻止冒泡
		event.stopPropagation();
	});
	//记录顶级菜单编号
	if (!sessionStorage.getItem('menuid')) {
		sessionStorage.setItem('menuid', "{{key($_site_menu_)}}");
	}
	//设置顶级菜单为选中样式
	if (sessionStorage.getItem('menuid')) {
		$("#top_menu_" + sessionStorage.getItem('menuid')).addClass('active');
	}
	//设置左侧菜单点击样式
	if (sessionStorage.getItem('dataHref')) {
		$("li[dataHref='" + sessionStorage.getItem('dataHref') + "']").addClass('active');
	}
	//显示当前左侧菜单
	$("[menuid='" + sessionStorage.getItem('menuid') + "']").removeClass('hide');
</script>

<!--<script>-->
<!--	require(['angular', 'util'], function (angular, util) {-->
<!--		angular.module('siteMenuApp', []).controller('_site_menus_', ['$scope', function ($scope) {-->
<!--		}]);-->
<!--		angular.bootstrap(document.getElementById('_site_menus_'), ['siteMenuApp'])-->
<!--	});-->
<!--</script>-->
</body>
</html>