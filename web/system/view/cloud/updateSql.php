<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">一键更新</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="#">系统更新</a></li>
	</ul>
	<form action="" class="form-horizontal ng-cloak" id="form" ng-cloak ng-controller="ctrl">
		<div class="panel panel-default" ng-if="error==''">
			<div class="panel-heading">
				正在更新数据表...
			</div>
			<div class="panel-body">
				<p style="margin: 0px;">
					@{{success}}
				</p>
			</div>
		</div>
		<!--执行失败-->
		<div class="panel panel-danger" ng-if="error!=''">
			<div class="panel-heading">
				更新失败
			</div>
			<div class="panel-body">
				@{{error}}
			</div>
		</div>
		<div class="alert alert-info">
			<p>Release版本: Build {{$data['lastVersion']['releaseCode']}} , 您可以将问题截图发到论坛求助</p>
		</div>
	</form>
</block>
<script>
	require(['angular', 'util'], function (angular, util) {
		angular.module(['myApp'], []).controller('ctrl', ['$scope', '$http', function ($scope, $http) {
			$scope.error = '';
			$scope.success = '';
			$scope.upgrade = function () {
				//执行更新
				$.post("{{__URL__}}", function (res) {
					if (res.valid == 2) {
						util.message('更新完成', "{{u('upgrade')}}", 'success');
					} else if (res.valid == 0) {
						$scope.error = res.sql + " 执行失败";
					} else if (res.valid == 1) {
						$scope.success = res.sql + " 执行成功";
					}
					$scope.$apply();
				}, 'json');
			}
			$scope.upgrade();
		}]);
		angular.bootstrap(document.getElementById('form'), ['myApp']);
	})
</script>