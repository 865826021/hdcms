<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">应用商店</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation"><a href="{{u('installed')}}">已经安装模块</a></li>
		<li role="presentation"><a href="?s=system/module/prepared">安装模块</a></li>
		<li role="presentation"><a href="?s=system/module/design">设计新模块</a></li>
		<li role="presentation"><a href="{{u('shop.lists',['type'=>'module'])}}">模块商城</a></li>
		<li role="presentation" class="active"><a href="{{u('shop.upgrade')}}">模块更新</a></li>
	</ul>
	<div class="clearfix ng-cloak" ng-controller="ctrl" ng-cloak>
		<div class="row" ng-if="field.valid==0">
			<div class="col-sm-12 col-md-12">
				<div class="alert alert-danger">
					@{{field.message}}
				</div>
			</div>
		</div>
		<div class="row" ng-show="field.valid==undefined">
			<div class="col-sm-12 col-md-12">
				<div class="alert alert-info">
					正在获取模块更新列表
				</div>
			</div>
		</div>
		<div class="row" ng-show="field.valid==1">
			<div class="col-sm-4 col-md-2" ng-repeat="v in field.apps" ng-show="field.apps.length>0">
				<div class="thumbnail">
					<img ng-src="@{{'http://dev.hdcms.com/'+v.app_preview}}"
					     style="height: 200px; width: 100%; display: block;">
					<div class="caption">
						<h3>@{{v.title}}</h3>
						<p>@{{v.resume}}</p>
						<p>
							<a ng-if="v.message=='开始更新'" ng-click="upgrade(v)" class="btn btn-default"
							   role="button">
								@{{v.message}}
							</a>
							<a ng-if="v.message=='正在更新...'" class="btn btn-primary"
							   role="button">
								@{{v.message}}
							</a>
							<a ng-if="v.message=='更新完毕'" class="btn btn-success"
							   role="button">
								@{{v.message}}
							</a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-12" ng-show="field.apps.length==0">
				<div class="alert alert-info">
					恭喜! 所有模块都是最新版本。
				</div>
			</div>
		</div>
	</div>
</block>

<script>
	require(['angular', 'util', 'underscore', 'jquery', 'angular.sanitize'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', ['ngSanitize']).controller('ctrl', ['$scope', '$sce', function ($scope, $sce) {
				$scope.field = {'apps': [], 'page': ''};
				//起始页
				$.post("{{u('shop.upgradeLists')}}", function (json) {
					if (json.valid == 1) {
						$scope.field = json;
						for (var i = 0; i < $scope.field.apps.length; i++) {
							$scope.field.apps[i].message = '开始更新';
						}
						$scope.field.page = $sce.trustAsHtml($scope.field.page);
					} else {
						$scope.error = json.message;
					}
					$scope.$apply();
				}, 'json');

				//更新模块
				$scope.upgrade = function (v) {
					v.message='正在更新...';
					$.post("{{u('upgrade')}}&name="+v.name, function (json) {
						if(json.valid==0){
							v.message=json.message;
						}else{
							v.message='更新完毕';
						}
						$scope.$apply();
					}, 'json');
				}
			}])

			angular.bootstrap(document.body, ['app']);
		})
	})
</script>
