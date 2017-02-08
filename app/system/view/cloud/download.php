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
	<div class="alert alert-success">
		<i class="fa fa-info-circle"></i>
		新版本的文件已经下载完毕并进行了更新。<br/>
		<i class="fa fa-info-circle"></i>
		旧版本的文件已经移动到了data/upgrade目录中,如果更新出现异常可以进行手动将文件复制回来恢复到旧版本。
	</div>
	<form action="" class="form-horizontal ng-cloak" ng-cloak id="form" ng-controller="ctrl">
		<div class="panel panel-default" ng-show="files.length==0">
			<div class="panel-heading">
				正在下载文件，请不要关闭浏览器窗口
			</div>
			<div class="panel-body">
				<div class="progress">
					<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default" ng-show="files.length>0">
			<div class="panel-heading">
				本次更新的文件列表
			</div>
			<div class="panel-body">
				<p style="margin: 0px;">
						<span class="text-info" ng-repeat="(k,v) in files" style="display: block;">
							@{{v.file}}
							<i class="fa fa-check-circle-o alert-success" ng-if="v.status==1"></i>
							<i class="fa fa-times-circle-o alert-danger" ng-if="v.status==0"></i>
						</span>
				</p>

				<span class="text-info" ng-repeat="(k,v) in files" style="display: block;">
				<i class="fa fa-check-circle-o alert-success"></i> @{{v}}
			</span>
			</div>
		</div>
		<a href="" class="btn btn-success">更新数据表 <small>[ 更新前! 请务必对数据表进行备份 ]</small></a>
	</form>
</block>
<script>
	require(['util', 'angular', 'underscore'], function (util, angular, _) {
		angular.module('app', []).controller('ctrl', ['$scope', '$http', function ($scope, $http) {
			$scope.files = [];
			$.get("{{u('upgrade',['action'=>'downloadFile'])}}", {}, function (files) {
				$scope.files = files;
				$scope.$apply();
			}, 'json')
		}]);
		angular.bootstrap(document.getElementById('form'), ['app']);
	});
</script>