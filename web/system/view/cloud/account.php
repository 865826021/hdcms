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
				<a href="javascript:;" ng-click="changAction('bind')" ng-if="action=='show'">更改</a>
				<a href="javascript:;" ng-click="changAction('show')" ng-if="action=='bind'">显示</a>
			</div>
			<div class="panel-body" ng-if="action=='bind'">
				<div class="form-group">
					<label class="col-sm-2 control-label">云帐号</label>
					<div class="col-sm-8">
						<input type="text" name="username" class="form-control" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">云密码</label>
					<div class="col-sm-8">
						<input type="password" name="password" class="form-control" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">网站名称</label>
					<div class="col-sm-8">
						<input type="text" name="webname" value="{{$field['webname']}}" class="form-control" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">网站URL</label>
					<div class="col-sm-8">
						<input type="text" name="weburl" disabled="disabled" value="{{__ROOT__}}" class="form-control">
					</div>
				</div>
				<button class="btn btn-default col-sm-offset-2">开始绑定</button>
			</div>
			<div class="panel-body" ng-if="action=='show'">
				<div class="form-group">
					<label class="col-sm-2 control-label">云帐号</label>
					<div class="col-sm-8">
						<p class="form-control-static">{{$field['username']}}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">网站名称</label>
					<div class="col-sm-10">
						<p class="form-control-static">{{$field['webname']}}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">通信编号(AppID)</label>
					<div class="col-sm-10">
						<p class="form-control-static">{{$field['AppID']}}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">应用密钥(AppSecret)</label>
					<div class="col-sm-10">
						<p class="form-control-static">{{$field['AppSecret']}}</p>
					</div>
				</div>
			</div>
		</div>
	</form>
</block>
<script>
	require(['angular', 'util'], function (angular, util) {
		angular.module('myApp', []).controller('ctrl', ['$scope', function ($scope) {
			$scope.action = 'show';
			$scope.changAction = function (action) {
				$scope.action = action;
			}
			$("form").submit(function () {
				$.post('{{__URL__}}', $("form").serialize(), function (res) {
					if (res.valid == 1) {
						util.message('连接成功', 'refresh', 'success');
					} else {
						util.message(res.message, '', 'error');
					}
				}, 'json');
				return false;
			})
		}]);
		angular.bootstrap(document.getElementById('form'), ['myApp']);
	});
</script>