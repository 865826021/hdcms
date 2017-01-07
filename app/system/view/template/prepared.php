<extend file="resource/view/system"/>
<link rel="stylesheet" href="{{__VIEW__}}/template/css.css">
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">已经安装模板</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation"><a href="{{u('installed')}}">已经安装模板</a></li>
		<li role="presentation" class="active"><a href="?s=system/template/prepared">安装模板</a></li>
		<li role="presentation"><a href="?s=system/template/design">设计新模板</a></li>
		<li role="presentation"><a href="{{c('api.cloud')}}?a=site/store&t=web&siteid=1&m=store&type=theme" target="_blank">应用商城</a></li>
	</ul>
	<h5 class="page-header">已购买的模板</h5>
	<div id="myApp" ng-controller="ctrl" class="ng-cloak template clearfix" ng-cloak>
		<div class="thumbnail" ng-repeat="a in cloudApps">
			<h5>@{{a.manifest.application.title['@cdata']}}(@{{a.manifest.application.name['@cdata']}})</h5>
			<img class="media-object"
			     ng-src="{{dirname(c('api.cloud'))}}/packages/theme/@{{a.manifest.application.name['@cdata']}}/@{{a.manifest.application.thumb['@cdata']}}"/>
			<div class="caption">
				<a class="btn btn-default btn-xs btn-block" href="{{u('install')}}&name=@{{a.manifest.application.name['@cdata']}}">安装模板</a>
			</div>
		</div>
	</div>
	<h5 class="page-header">未安装的本地模板</h5>
	<div class="template">
		<foreach from="$locality" value="$m">
			<if value="$m['locality']">
				<div class="thumbnail action">
					<h5>{{$m['title']}}({{$m['name']}})</h5>
					<img class="media-object" src="{{$m['thumb']}}"/>
					<div class="caption">
						<if value="$m['locality']==1">
							<a class="btn btn-default btn-xs" style="width: 45%" href="{{u('install',array('name'=>$m['name']))}}">安装模板</a>
							<a class="btn btn-default btn-xs" style="width: 45%" href="{{u('createZip',array('name'=>$m['name']))}}">打包下载</a>
							<else/>
							<a class="btn btn-default btn-xs btn-block" style="width: 45%" href="{{u('install',array('name'=>$m['name']))}}">安装模板</a>
						</if>
					</div>
				</div>
			</if>
		</foreach>
	</div>
</block>
<script>
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
