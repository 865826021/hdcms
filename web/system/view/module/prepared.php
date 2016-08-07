<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">已经安装模块</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation"><a href="{{u('installed')}}">已经安装模块</a></li>
		<li role="presentation" class="active"><a href="?s=system/module/prepared">安装模块</a></li>
		<li role="presentation"><a href="?s=system/module/design">设计新模块</a></li>
		<li role="presentation"><a href="{{c('api.cloud')}}?a=site/store&t=web&siteid=1&m=store">应用商城</a></li>
	</ul>
	<h5 class="page-header">已购买的模块</h5>
	<div id="myApp" ng-controller="ctrl" class="ng-cloak" ng-cloak>
		<div class="media" ng-repeat="a in cloudApps">
			<div class="pull-right">
				<div style="margin-right: 10px;">
					<a href="{{u('install')}}&module=@{{a.manifest.application.name['@cdata']}}">安装模块</a>
				</div>
			</div>
			<div class="media-left">
				<a href="#">
					<img class="media-object"
					     ng-src="{{dirname(c('api.cloud'))}}/packages/addons/@{{a.manifest.application.name['@cdata']}}/@{{a.manifest.application.thumb['@cdata']}}"
					     style="width: 50px;height: 50px;">
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading">{{$local['title']}}
					<small>（标识：@{{a.manifest.application.name['@cdata']}} 版本：@{{a.manifest.application.version['@cdata']}}
						作者：@{{a.manifest.application.author['@cdata']}}）
					</small>
				</h4>
				<a href="javascript:;" class="detail">详细介绍</a>
			</div>
			<div class="alert alert-info" role="alert"><strong>功能介绍</strong>： @{{a.manifest.application.detail['@cdata']}}</div>
		</div>
	</div>
	<h5 class="page-header">未安装的本地模块</h5>
	<foreach from="$locality" value="$local">
		<if value="$local['locality']">
			<div class="media">
				<div class="pull-right">
					<div style="margin-right: 10px;">
						<a href="{{u('install',array('module'=>$local['name']))}}">安装模块</a>
					</div>
				</div>
				<div class="media-left">
					<a href="#">
						<img class="media-object" src="{{$local['cover']}}" style="width: 50px;height: 50px;">
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading">{{$local['title']}}
						<small>（标识：{{$local['name']}} 版本：{{$local['version']}} 作者：{{$local['author']}}）</small>
					</h4>
					<a href="javascript:;" class="detail">详细介绍</a>
				</div>
				<div class="alert alert-info" role="alert"><strong>功能介绍</strong>： {{$local['detail']}}</div>
			</div>
		</if>
	</foreach>
	<style>
		.media {
			border-bottom  : solid 1px #dcdcdc;
			padding-bottom : 6px;
		}

		.media h4.media-heading {
			font-size : 16px;
		}

		.media .media-left a img {
			border-radius : 5px;
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
		$('body').delegate('.detail', 'click', function () {
			//隐藏所有详细介绍内容
			$('.detail').not(this).parent().next().hide();
			//显示介绍
			$(this).parent().next().toggle();
		});
		require(['util', 'angular'], function (util, angular) {
			angular.module('myApp', []).controller('ctrl', ['$scope', '$http', function ($scope, $http) {
				$scope.cloudApps = [];
				$http.get('{{u("getCloudModules")}}').success(function (res) {
					$scope.cloudApps = res;
					console.log(res);
				});
			}]);
			angular.bootstrap(document.getElementById('myApp'), ['myApp']);
		});
	</script>
</block>
