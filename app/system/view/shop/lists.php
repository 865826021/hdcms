<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">应用商店</li>
	</ol>
	<ul class="nav nav-tabs">
		<if value="$_GET['type']=='module'">
            <li role="presentation"><a href="{{u('module.installed')}}">已经安装模块</a></li>
            <li role="presentation"><a href="{{u('module.prepared')}}">安装模块</a></li>
            <li role="presentation"><a href="{{u('module.design')}}">设计新模块</a></li>
			<li role="presentation" class="active"><a href="javascript:;">模块商城</a></li>
            <li role="presentation"><a href="{{u('shop.upgradeLists')}}">模块更新</a></li>
            <li role="presentation"><a href="{{u('shop.buy',['type'=>'module'])}}">已购模块</a></li>
			<else/>
            <li role="presentation"><a href="{{u('template.installed')}}">已经安装模板</a></li>
            <li role="presentation"><a href="{{u('template.prepared')}}">安装模板</a></li>
            <li role="presentation"><a href="{{u('template.design')}}">设计新模板</a></li>
			<li role="presentation" class="active"><a href="javascript:;">模板商城</a></li>
            <li role="presentation"><a href="{{u('shop.buy',['type'=>'template'])}}">已购模板</a></li>
		</if>
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
					正在获取应用列表
				</div>
			</div>
		</div>
		<div class="row" ng-show="field.valid==1">
			<div class="col-sm-4 col-md-2" ng-repeat="v in field.apps">
				<div class="thumbnail">
					<img ng-src="@{{v.app_preview}}"
					     style="height: 200px; width: 100%; display: block;">
					<div class="caption">
						<h4>
							@{{v.title}}
						</h4>
						<small>
							价格: <span ng-show="v.price>0" >@{{v.price}} 元</span>
							<span ng-show="v.price<=0" class="label label-info">免费</span>
                            <span ng-show="v.audit==0" class="label label-danger">测试版</span>
						</small>
						<p class="resume">@{{v.resume}}</p>
						<p>
							<a ng-if="!v.is_install" ng-click="install(v)" class="btn btn-primary btn-sm btn-block"
							   role="button">开始安装</a>
						</p>
						<p><span ng-if="v.is_install" class="disabled btn btn-default btn-sm btn-block">已经安装</span></p>
					</div>
				</div>
			</div>
		</div>
		<div ng-bind-html="field.page" class="pagination"></div>
	</div>
</block>
<script>
	require(['angular', 'util', 'underscore', 'jquery', 'angular.sanitize'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', ['ngSanitize']).controller('ctrl', ['$scope', '$sce', function ($scope, $sce) {
				$scope.field = {'apps': [], 'page': ''};
				//起始页
				$scope.get = function (page) {
					$.post("{{u('shop.lists',['type'=>$_GET["type"]])}}&page=" + page, function (json) {
						$scope.complete = true;
						$scope.field = json;
						if (json.valid == 1) {
							$scope.field.page = $sce.trustAsHtml($scope.field.page);
						} else {
							$scope.message = json.message;
						}
						$scope.$apply();
					}, 'json');
				}
				$scope.get(1);
				$('.pagination').delegate('li a', 'click', function () {
					$scope.get($(this).text());
					return false;
				})
				//安装模块
				$scope.install = function (module) {
					$.post("{{u('install',['type'=>$_GET['type']])}}&id="+module.id, function (json) {
						if(json.valid==0){
							util.message(json.message,'','warning',8);
						}else{
							util.message(json.message,json.url,'success',3);
						}
					}, 'json');
				}
			}])
			angular.bootstrap(document.body, ['app']);
		})
	})
</script>
<style>
    .thumbnail img{
        border:solid 1px #ddd;
        height: 200px; width: 100%; display: block;
    }
    .thumbnail .resume{
        overflow:hidden;;/* 内容超出宽度时隐藏超出部分的内容 */
        text-overflow:ellipsis;;/* 当对象内文本溢出时显示省略标记(...) ；需与overflow:hidden;一起使用。*/
        /*white-space:nowrap;!* 不换行 *!*/
        height:40px;
        font-size:14px;
        margin-top: 6px;
    }
</style>