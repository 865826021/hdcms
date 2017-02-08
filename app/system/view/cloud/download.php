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
		<div class="panel panel-default">
			<div class="panel-heading">
				本次更新的文件列表
			</div>
			<div class="panel-body">
				sdf
			</div>
		</div>
		<a href="" class="btn btn-success">更新数据表 <small>[ 更新前! 请务必对数据表进行备份 ]</small></a>
	</form>
</block>
<script>
	require(['util', 'angular', 'underscore'], function (util, angular, _) {
		angular.module('app', []).controller('ctrl', ['$scope', '$http', function ($scope, $http) {
			$scope.files = <?php echo json_encode( $data['data']['files'] );?>;
			angular.forEach($scope.files, function (v, k) {
				$scope.files[k] = {downloaded: null, file: v};
			});
			var pos = 0;
			$scope.download = function () {
				$.get("{{u('upgrade',['action'=>'download'])}}", {}, function (res) {
					if (res.valid == 0) {
						//更新失败
						util.message($scope.files[pos].file+' 更新失败<br/> 请稍候再试或加入官方QQ群获取帮助','','warning');
					} else if (res.valid == 1) {
						//更新成功
						$scope.files[pos].downloaded = true;
						pos++;
						$scope.$apply();
						$scope.download();
					} else if (res.valid == 2) {
						//更新完成
						location.href = "{{u('upgrade',['action'=>'sql'])}}";
					}
				}, 'json')
			}
			$scope.download();
		}]);
		angular.bootstrap(document.getElementById('form'), ['app']);
	});
</script>