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
						<p><a ng-if="!v.is_install" href="{{u('install',['type'=>'module'])}}&id=@{{v.id}}" class="btn btn-primary" role="button">安装应用</a></p>
						<p><span ng-if="v.is_install" class="btn btn-default">已经安装</span></p>
					</div>
				</div>
			</div>
		</div>
		<div ng-bind-html="field.page" class="pagination"></div>
	</div>
</block>

<script>
	require(['angular', 'util', 'underscore', 'jquery','angular.sanitize'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', ['ngSanitize']).controller('ctrl', ['$scope','$sce', function ($scope,$sce) {
				$scope.field = {'apps': [], 'page': ''};
				//起始页
				$scope.get = function (page){
					$.get("{{u('shop.getCloudLists')}}", {type: 'module',page:page}, function (json) {
						$scope.field = json;
						$scope.field.page =$sce.trustAsHtml($scope.field.page);
						$scope.$apply();
					}, 'json');
				}
				$scope.get(1);
				$('.pagination').delegate('li a','click',function(){
					$scope.get($(this).text());
					return false;
				})
			}])

			angular.bootstrap(document.body, ['app']);
		})
	})
</script>
