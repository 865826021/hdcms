<extend file="resource/view/system"/>
<block name="content">
	<div class="clearfix">
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i></li>
			<li><a href="?s=system/manage/menu">系统</a></li>
			<li class="active">设置新模板</li>
		</ol>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="{{u('installed')}}">已经安装模板</a></li>
			<li role="presentation"><a href="?s=system/template/prepared">安装模板</a></li>
			<li role="presentation" class="active"><a href="javascript:;">设计新模板</a></li>
			<li role="presentation"><a href="{{c('api.cloud')}}?a=site/store&t=web&siteid=1&m=store&type=theme" target="_blank">应用商城</a></li>
		</ul>
		<form action="" ng-controller="ctrl" class="form-horizontal form" method="post" enctype="multipart/form-data">
			<h5 class="page-header">模板基本信息
				<small>这里来定义你自己模板的基本信息</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板名称</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.title">
					<span class="help-block">模板的名称, 由于显示在用户的模板列表中. 不要超过10个字符 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板标识</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.name">
					<span class="help-block">模板标识符, 对应模板文件夹的名称, 系统按照此标识符查找模板定义, 只能由字母数字下划线组成 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">版本</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.version">
					<span class="help-block">模板当前版本, 此版本号用于模板的版本更新 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板类型</label>
				<div class="col-sm-10 col-xs-12">
					<select ng-model="field.industry" class="form-control"
					        ng-options="c.name as c.title for c in industry"></select>
					<span class="help-block">模板的类型, 用于分类展示和查找你的模板 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板简述</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.resume">
					<span class="help-block">模板功能描述, 使用简单的语言描述模板, 来吸引用户 </span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">作者</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.author">
					<span class="help-block">模板的作者, 留下你的大名吧 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">发布页</label>

				<div class="col-sm-10 col-x s-12">
					<input type="text" class="form-control" ng-model="field.url">
					<span class="help-block">模板的发布页, 用于发布模板更新信息的页面 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">微站导航菜单数量</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.position">
					<span class="help-block">微站导航菜单数量 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板缩略图</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="text" class="form-control" readonly="" ng-model="field.thumb">
						<div class="input-group-btn">
							<button ng-click="uploadThumb()" class="btn btn-default" type="button">选择图片</button>
						</div>
					</div>
					<div class="input-group" style="margin-top:5px;">
						<img ng-src="@{{field.thumb}}" class="img-responsive img-thumbnail img-thumb" width="150">
						<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片"
						    ng-click="field.thumb='resource/images/nopic.jpg'">×</em>
					</div>
					<span class="help-block">图片尺寸为225x170 会有良好的显示效果</span>
				</div>
			</div>
			<input type="hidden" name="data">
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<input name="method" type="hidden" value="download">
					<input name="token" type="hidden" value="6708fa25">
					<input type="submit" class="btn btn-primary" id="createBtn" name="submit" onclick="$(':hidden[name=method]').val('create');"
					       value="生成模板">
					<p class="help-block">点此直接在源码目录 theme<span class="identifie"></span> 处生成模板文件, 方便快速开发</p>
				</div>
			</div>
		</form>
	</div>
</block>

<script>
	require(['angular', 'util', 'underscore', 'jquery'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
				$scope.industry = [
					{title: '常用模板', name: 'often'},
					{title: '酒店', name: 'rummery'},
					{title: '汽车', name: 'car'},
					{title: '旅游', name: 'tourism'},
					{title: '餐饮', name: 'drink'},
					{title: '房地产', name: 'realty'},
					{title: '医疗保健', name: 'medical'},
					{title: '教育', name: 'education'},
					{title: '健身美容', name: 'cosmetology'},
					{title: '婚纱摄影', name: 'shoot'},
					{title: '其他', name: 'other'}
				];
				$scope.field = {
					"title": "aaa",
					"name": "hdcms",
					"version": "1.0",
					"industry": "often",
					"resume": "resume",
					"author": "author",
					"detail": "detail",
					"url": "url",
					"position": 10,
					"thumb": "resource/images/nopic.jpg",
				};
				$scope.uploadThumb = function () {
					util.image(function (images) {
						$scope.field.thumb = images[0];
						$scope.$apply();
					})
				}

				$("form").submit(function () {
					var msg = ''
					if ($scope.field.title == '') {
						msg += '模板名称不能为空<br/>';
					}
					if ($scope.field.name == '') {
						msg += '模板标识不能为空<br/>';
					}
					if ($scope.field.resume == '') {
						msg += '模板简述不能为空<br/>';
					}
					if ($scope.field.detail == '') {
						msg += '模板介绍不能为空<br/>';
					}
					if ($scope.field.author == '') {
						msg += '模板作者不能为空<br/>';
					}
					if ($scope.field.url == '') {
						msg += '发布网址不能为空<br/>';
					}
					if ($scope.field.thumb == '') {
						msg += '模板缩略图不能为空<br/>';
					}
					if (msg != '') {
						util.message(msg, '', 'warning');
						return false;
					}
					$("[name='data']").val(angular.toJson($scope.field));
				})
			}])

			angular.bootstrap(document.body, ['app']);
		})

	})
</script>