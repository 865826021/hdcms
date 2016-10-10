<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">注册选项</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="javascript:;">注册选项</a></li>
	</ul>
	<h5 class="page-header">注册设置</h5>

	<form action="" class="form-horizontal ng-cloak" ng-cloak method="post" id="form" ng-controller="myController">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">是否开启用户注册</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.is_open"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.is_open"> 否
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">是否审核新用户</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.audit"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.audit"> 否
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">启用注册验证码</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.enable_code"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.enable_code"> 否
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">默认所属用户组</label>

					<div class="col-sm-6">
						<select class="form-control" ng-options="a.id as a.name for a in group" ng-model="field.groupid">
						</select>
						<span class="help-block">当开启用户注册后，新注册用户将会分配到该用户组里，并直接拥有该组的模块操作权限。</span>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="register">
		<button class="btn btn-primary">提交</button>
	</form>
</block>

<script>
	require(['angular', 'util'], function (angular, util) {
		angular.module('myApp', []).controller('myController', ['$scope', function ($scope) {
			$scope.field =<?php echo $field ? json_encode( $field ) : '{"is_open":0,"audit":0,"enable_code":1}';?>;
			$scope.group =<?php echo json_encode( $group );?>;
			$('form').submit(function () {
				$('[name="register"]').val(angular.toJson($scope.field));
			})
		}]);

		angular.bootstrap(document.getElementById('form'), ['myApp']);
	})
</script>
