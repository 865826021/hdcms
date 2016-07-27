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
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-info">
				<div class="panel-body alert-info">
					<h4 style="margin-top: 0px;"><i class="fa fa-refresh"></i> 更新日志</h4>
					<p>
						<a href="">微擎0.7更新说明【2016年07月26日】</a>
						<span class="pull-right">2016-07-26</span>
					</p>
					<p>
						<a href="">微擎0.7更新说明【2016年07月26日】</a>
						<span class="pull-right">2016-07-26</span>
					</p>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="panel panel-info">
				<div class="panel-body alert-info">
					<h4 style="margin-top: 0px;"><i class="fa fa-bullhorn"></i> 系统公告</h4>
					<p>
						<a href="">微擎运营学院征集大赛，文案策划有福利啦！（抢楼送交易币）</a>
						<span class="pull-right">2016-07-26</span>
					</p>
					<p>
						<a href="">微擎0.7更新说明【2016年07月26日】</a>
						<span class="pull-right">2016-07-26</span>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="alert alert-danger">
		<i class="fa fa-exclamation-triangle"></i> 更新时请注意备份网站数据和相关数据库文件！官方不强制要求用户跟随官方意愿进行更新尝试！
	</div>
	<form action="" class="form-horizontal ng-cloak" method="post" id="form" ng-cloak ng-controller="ctrl">
		<if value="$data['valid']==1">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">更新文件列表</h3>
				</div>
				<div class="panel-body">
					<p ng-repeat="(k,v) in data.message.upgrade_files" style="margin: 0px;">
						<span class="text-info">@{{k}} <i class="fa fa-check" ng-if="v"></i> [M] </span> <br/>
						<span class="text-info">@{{k}} <i class="fa fa-check" ng-if="v"></i> [U] </span> <br/>

					</p>
				</div>
			</div>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">数据更新</h3>
				</div>
				<div class="panel-body">
					<p ng-repeat="(k,v) in data.message.upgrade_files" style="margin: 0px;">
						<span class="text-info">@{{k}} <i class="fa fa-check" ng-if="v"></i> [M] </span> <br/>
						<span class="text-info">@{{k}} <i class="fa fa-check" ng-if="v"></i> [U] </span> <br/>

					</p>
				</div>
			</div>
			<div class="alert alert-danger" ng-if="alldown">
				<strong>系统更新完毕!</strong>
			</div>
			<button class="btn btn-primary" type="button" ng-click="download()" ng-if="!alldown">开始更新</button>
			<else/>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">更新日志</h3>
				</div>
				<div class="panel-body">
					@{{data.message}}
				</div>
			</div>
		</if>
	</form>
</block>
<script>
	require(['angular'], function (angular) {
		angular.module('myApp', []).controller('ctrl', ['$scope', '$http', '$interval', function ($scope, $http, $interval) {
			$scope.data =<?php echo json_encode( $_SESSION['_hdcms_upgrade'] );?>;
			//全部更新完毕
			$scope.alldown = false;
			$scope.download = function () {
				stop = $interval(function () {
					$http.post("{{__URL__}}").success(function (res) {
						console.log(res);
						if (res.alldown == 1) {
							$scope.alldown = true;
							//全部下载完成
							$interval.cancel(stop);
						} else {
							$scope.data.message.upgrade_files[res.file] = true;
						}
					})
				}, 500);
			}
		}]);
		angular.bootstrap(document.getElementById('form'), ['myApp']);
	})
</script>