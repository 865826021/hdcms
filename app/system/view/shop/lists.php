<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">已经安装模块</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation"><a href="?s=system/manage/menu">系统管理</a>
		<li role="presentation" class="active"><a href="{{u('shop.lists')}}">模块商城</a>
	</ul>
	<div class="clearfix ng-cloak" ng-controller="ctrl" ng-cloak>
		<div class="row">
			<div class="col-sm-4 col-md-2" ng-repeat="v in field.apps">
				<div class="thumbnail">
					<img ng-src="@{{'http://dev.hdcms.com/'+v.app_preview}}" style="height: 200px; width: 100%; display: block;">
					<div class="caption">
						<h3>@{{v.title}}</h3>
						<p>@{{v.resume}}</p>
						<p><a href="#" class="btn btn-primary" role="button">安装应用</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</block>

<script>
	require(['angular', 'util', 'underscore', 'jquery'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
				$scope.field = {'apps': [], 'page': ''};
				//起始编号
				var startId = 0;
				$.get("{{u('shop.getCloudLists')}}", {type: 'module', 'startId': startId}, function (json) {
					$scope.field = json;
					$scope.$apply();
				}, 'json');
			}])

			angular.bootstrap(document.body, ['app']);
		})

	})
</script>
