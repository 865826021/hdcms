<extend file="resource/view/system"/>
<block name="content">
	<div ng-controller="ctrl">
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i></li>
			<li><a href="?s=system/manage/menu">系统</a></li>
			<li class="active">已经安装模板</li>
		</ol>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="javascript:;">已经安装模板</a></li>
			<li role="presentation"><a href="?s=system/template/prepared">安装模板</a></li>
			<li role="presentation"><a href="?s=system/template/design">设计新模板</a></li>
			<li role="presentation"><a href="http://open.hdphp.com">应用商城</a></li>
		</ul>
		<h5 class="page-header">菜单列表</h5>

		<nav role="navigation" class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
					        aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">模板类型</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active"><a href="{{__URL__}}">全部</a></li>
						<li><a href="#" data-type="business">主要业务</a></li>
						<li><a href="#" data-type="customer">客户关系</a></li>
						<li><a href="#" data-type="marketing">营销与活动</a></li>
						<li><a href="#" data-type="tools">常用服务与工具</a></li>
						<li><a href="#" data-type="industry">行业解决方案</a></li>
						<li><a href="#" data-type="other">其他</a></li>
					</ul>
					<form class="navbar-form navbar-left" role="search" method="post">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="搜索模板" id="search">
						</div>
					</form>
				</div>
			</div>
		</nav>
		<foreach from="$template" value="$m">
			<div class="media" type="{{$m['type']}}">
				<div class="pull-right">
					<div style="margin-right: 10px;">
						<if value="$m['type']==1">
							<span class="label label-primary">系统内置模板</span>
							<else/>
							<span class="label label-success">本地安装模板</span>&nbsp;&nbsp;&nbsp;
							<a href="javascript:;" onclick="uninstall('{{$m['name']}}','{{$m['title']}}')">卸载</a>
						</if>
					</div>
				</div>
				<div class="media-left">
					<a href="#">
						<img class="media-object" src="{{$m['thumb']}}" style="width: 50px;height: 50px;"/>
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading">{{$m['title']}}
						<small>（标识：{{$m['name']}} 版本：{{$m['version']}} 作者：{{$m['author']}}）</small>
					</h4>
					<a href="javascript:;" class="detail">详细介绍</a>
				</div>
				<div class="alert alert-info" role="alert" style="display: none"><strong>功能介绍</strong>： {{$m['description']}}</div>
			</div>
		</foreach>
	</div>
</block>

<style>
	.media {
		border-bottom  : solid 1px #dcdcdc;
		padding-bottom : 6px;
	}

	.media h4.media-heading {
		font-size : 16px;
	}

	.media .media-left a img {
		border-radius : 10px;
	}

	.media h4.media-heading small {
		font-size : 65%;
	}

	.media .media-body a {
		display     : inline-block;
		font-size   : 14px;
		padding-top : 6px;
	}

	.media .alert {
		margin-top : 6px;
		display    : none;
	}
</style>
<script>
	//模板介绍
	$('.detail').click(function () {
		//隐藏所有详细介绍内容
		$('.detail').not(this).parent().next().hide();
		//显示介绍
		$(this).parent().next().toggle();
	});

	//点击模板类型显示列表
	$(".navbar-nav [data-type]").click(function () {
		//类型
		var dataType = $(this).attr('data-type');
		$(".media").show().not('[type=' + dataType + ']').hide();
		$('.navbar-nav').find('li').removeClass('active');
		$(this).parent().addClass('active');
	});

	$("#search").keyup(function () {
		var word = $(this).val();
		if (word == '') {
			//搜索词为空
			$(".media").show();
		} else {
			$(".media").each(function (i) {
				if ($(this).find('h4').text().indexOf(word) >= 0) {
					$(this).show();
				} else {
					$(this).hide();
				}
			})
		}
	})

	function uninstall(name, title) {
		util.confirm('确定删除 [' + title + '] 模板吗?', function () {
			location.href = '?s=system/template/uninstall&name=' + name;
		})
	}

</script>
