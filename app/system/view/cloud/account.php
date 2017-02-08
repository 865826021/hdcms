<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">云帐号</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="#">云帐号</a></li>
	</ul>
	<form action="" class="form-horizontal ng-cloak" method="post" id="form" ng-cloak ng-controller="ctrl">
		<!--云帐号-->
		<div class="panel panel-default">
			<div class="panel-heading">
				绑定信息
				<span class="label label-danger" ng-if="field.status==0">与云平台绑定失败</span>
				<span class="label label-success" ng-if="field.status==1">与云平台绑定成功</span>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">云帐号</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" required="required" ng-model="field.username">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">云密码</label>
					<div class="col-sm-8">
						<input type="password" class="form-control" required="required" ng-model="field.password">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">网站名称</label>
					<div class="col-sm-8">
						<input type="text" ng-model="field.webname" class="form-control" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">网站URL</label>
					<div class="col-sm-8">
						<input type="text" value="{{__ROOT__}}" disabled="disabled" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">应用密钥(secret)</label>
					<div class="col-sm-8">
						<div class="input-group">
							<input type="text" class="form-control" readonly="readonly" ng-model="field.secret" required="required">
							<span class="input-group-addon" style="cursor: pointer" ng-click="createAppSecret()">生成新的</span>
						</div>
					</div>
				</div>
				<textarea name="data" hidden="hidden"></textarea>
				<button class="btn btn-success col-sm-offset-2">重新与云平台绑定</button>
			</div>
		</div>
	</form>
</block>
<script>
	require(['angular', 'util','md5'], function (angular, util,md5) {
		angular.module('myApp', []).controller('ctrl', ['$scope', function ($scope) {
			$scope.field=<?php echo json_encode($field);?>;
			$scope.changAction = function (action) {
				$scope.action = action;
			}
			//生成createAppSecret
			$scope.createAppSecret=function(){
				$scope.field.secret=md5(Math.random());
			}
			$("form").submit(function () {
				$("[name='data']").val(angular.toJson($scope.field));
				var msg = '';
				$.post('{{__URL__}}', $("form").serialize(), function (res) {
					if (res.valid == 1) {
						util.message('连接成功', 'refresh', 'success');
					} else {
						util.message(res.message, 'refresh', 'error');
					}
				}, 'json');
				return false;
			})
		}]);
		angular.bootstrap(document.getElementById('form'), ['myApp']);
	});
</script>