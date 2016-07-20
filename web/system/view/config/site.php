<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">注册选项</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="javascript:;">站点选项</a></li>
	</ul>
	<h5 class="page-header">站点选项</h5>

	<form action="" class="form-horizontal ng-cloak" ng-cloak method="post" id="form" ng-controller="myController">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">开启站点</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.is_open"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.is_open"> 否
						</label>
					</div>
				</div>
				<div class="form-group" ng-show="field.is_open==0">
					<label class="col-sm-2 control-label">关闭原因</label>

					<div class="col-sm-6">
						<textarea class="form-control" ng-model="field.close_message" rows="6"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">开启登录验证码</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.enable_code"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.enable_code"> 否
						</label>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="site">
		<button class="btn btn-primary">提交</button>
	</form>
</block>

<script>
	require(['angular', 'util'], function (angular, util) {
		$
		angular.module('myApp', []).controller('myController', ['$scope', function ($scope) {
			$scope.field =<?php echo $field ? json_encode( $field ) : '{"is_open":0,"close_message":"网站维护中,请稍候访问","enable_code":1}';?>;
			$('form').submit(function () {
				$('[name="site"]').val(angular.toJson($scope.field));
			})
		}]);

		angular.bootstrap(document.getElementById('form'), ['myApp']);
	})
</script>
