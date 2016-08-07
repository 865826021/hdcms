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
	<div class="alert alert-danger">
		<i class="fa fa-exclamation-triangle"></i> 更新时请注意备份网站数据和相关数据库文件！官方不强制要求用户跟随官方意愿进行更新尝试！
	</div>
	<form action="" class="form-horizontal ng-cloak" ng-cloak id="form" ng-controller="ctrl">
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">发布日期</label>
			<div class="col-sm-10">
				<p class="form-control-static"><span class="fa fa-square-o"></span> &nbsp; 系统当前Release版本: Build {{$hdcms['releaseCode']}}</p>
				<div class="help-block">系统会检测当前程序文件的变动, 如果被病毒或木马非法篡改, 会自动警报并提示恢复至默认版本, 因此可能修订日期未更新而文件有变动</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				下载更新文件
			</div>
			<div class="panel-body">
				<p style="margin: 0px;">
						<span class="text-info" ng-repeat="(k,v) in files" style="display: block;">
							@{{v.file}}
							<i class="fa fa-check-circle-o alert-success" ng-if="v.downloaded==1"></i>
							<i class="fa fa-times-circle-o alert-danger" ng-if="v.downloaded==0"></i>
						</span>
				</p>
			</div>
		</div>
	</form>
</block>
<script>
	require(['util', 'angular', 'underscore'], function (util, angular, _) {
		angular.module('myApp', []).controller('ctrl', ['$scope', '$http', function ($scope, $http) {
			$scope.files = <?php echo json_encode( $data['data']['files'] );?>;
			angular.forEach($scope.files, function (v, k) {
				$scope.files[k] = {downloaded: null, file: v};
			});
			//执行下载
			var i=0;
			$scope.download = function () {
				$.get("{{u('upgrade',['action'=>'download'])}}",{i:i},function(res){
					if (res.valid == 0) {
						//更新失败
						$scope.files[i].downloaded = 0;
					} else if (res.valid == 1) {
						//更新成功
						$scope.files[i].downloaded = 1;
					} else if (res.valid == 2) {
						//更新完成
						location.href = "{{u('upgrade',['action'=>'finish'])}}";
						return;
					}
					i++;
					$scope.download();
					$scope.$apply();
				},'json')
			}
			$scope.download();
		}]);
		angular.bootstrap(document.getElementById('form'), ['myApp']);
	});
</script>