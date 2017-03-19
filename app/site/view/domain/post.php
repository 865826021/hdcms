<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li class="active"><a href="javascript:;">域名设置</a></li>
	</ul>
	<div class="alert alert-info">
		如果域名在其他模块或站点使用系统将忽略添加
	</div>
	<form action="" class="form-horizontal form ng-cloak" ng-controller="ctrl" method="post" ng-submit="submit($evnet)">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">域名设置</h3>
			</div>
			<div class="panel-body">
				<div class="form-group" ng-repeat="v in data">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-11">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">域名</span>
								<input class="form-control" ng-model="v.domain" type="text" name="domain[]">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-1">
							<div style="margin-left:-45px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<a href="javascript:;" ng-click="del(v)" class="fa fa-times-circle"
									   title="删除此操作"></a>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-10 col-xs-12 col-md-9 col-md-offset-1">
						<div class="well well-sm">
							<a href="javascript:;" ng-click="add()">
								添加域名 <i class="fa fa-plus-circle" title="添加菜单"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-primary">保存修改</button>
	</form>
</block>
<script>
	require(['angular', 'util', 'underscore', 'jquery'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
				$scope.data = <?php echo json_encode($data);?>;
				$scope.add = function () {
					$scope.data.push({domain: ""});
				}
				$scope.del = function (item) {
					$scope.data = _.without($scope.data,item);
				}
				$scope.submit=function(event){
				    event.preventDefault();
                    util.submit({successUrl:'refresh'});
                }
			}])
			angular.bootstrap(document.body, ['app']);

		})

	})
</script>