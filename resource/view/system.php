<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>HDCMS - 免费开源多站点管理系统</title>
	<include file="resource/view/common.php"/>
    <include file="resource/view/hdjs.php"/>
</head>
<body class="system">
<div class="container-fluid admin-top ">
	<!--导航-->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<ul class="nav navbar-nav">
					<if value="q('session.system.login')=='hdcms'">
						<li>
							<a href="?s=system/manage/menu"><i class="fa fa-w fa-cogs"></i> 系统管理</a>
						</li>
					</if>
					<if value="v('site')">
						<li>
							<a href="?s=site/entry/home&siteid={{SITEID}}" target="_blank">
								<i class="fa fa-share"></i> 继续管理公众号 ({{v('site.info.name')}})
							</a>
						</li>
					</if>
					<li>
						<a href="http://www.kancloud.cn/houdunwang/hdcms/169716" target="_blank"><i
								class="fa fa-w fa-file-code-o"></i> 在线文档</a>
					</li>
					<li>
						<a href="http://bbs.houdunwang.com" target="_blank"><i class="fa fa-w fa-forumbee"></i> 论坛讨论</a>
					</li>
				</ul>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<if value="v('site') && q('session.system.login')=='hdcms'">
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
							   style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "
							   aria-expanded="false">
								<i class="fa fa-group"></i> {{v('site.info.name')}} <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="?s=system/site/edit&siteid={{SITEID}}"><i class="fa fa-weixin fa-fw"></i>
										编辑当前账号资料</a></li>
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
								<li role="separator" class="divider"></li>
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
<div class="container-fluid  system-container">
	<div class="container-fluid" style="margin-top: 30px;margin-bottom: 20px;">
		<div class="col-md-6"
		     style="background: url('resource/images/logo.png') no-repeat;background-size: contain;height: 60px;opacity: .9;">
		</div>
		<div class="col-md-6">
			<ul class="nav nav-pills pull-right">
				<if value="q('session.system.login')=='hdcms'">
					<li>
						<a href="?s=system/site/lists" class="tile <if value='ACTION==" lists"'>active
				</if>
				">
				<i class="fa fa-sitemap fa-2x"></i>网站管理
				</a>
				</li>
				<li>
					<a href="?s=system/manage/menu" class="tile <if value='ACTION==" menu"'>active</if>">
					<i class="fa fa-support fa-2x"></i>系统设置
					</a>
				</li>
				</if>
				<li>
					<a href="?s=system/entry/quit" class="tile">
						<i class="fa fa-sign-out fa-2x"></i>退出
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="well clearfix">
		<blade name="content"/>
	</div>
	<div class="text-muted footer">
		<a href="http://www.houdunwang.com">高端培训</a>
		<a href="http://www.hdphp.com">开源框架</a>
		<a href="http://bbs.houdunwang.com">后盾论坛</a>
		<br/>
		Powered by hdcms <?php $cloud = Db::table( 'cloud' )->first() ?>
		{{$cloud['version']}} Build: {{date('Y-m-d H:i:s',$cloud['build'])}} © 2014-2019 www.hdcms.com
	</div>
	<div class="hdcms-upgrade">
		<a href="{{u('system/cloud/upgrade')}}"><span class="label label-danger">亲:) 有新版本了,快更新吧</span></a>
	</div>
	<style>
		.hdcms-upgrade {
			position: absolute;
			top: 0px;
			left: 45%;
			display: none;
		}
	</style>
</div>
<script>
	require(['bootstrap', 'util'], function ($, util) {
		var n = Math.floor(Math.random() * 10);
		if (n > 0) {
			$.get('{{u("cloud/getUpgradeVersion")}}', function (res) {
				if (res.valid == 1) {
					//有新版本
					$(".hdcms-upgrade").show();
				}
			}, 'json')
		}
	})
</script>
</body>
</html>
