<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li class="active"><a href="javascript:;">路由规则 </a></li>
	</ul>

	<form action="" class="form-horizontal form ng-cloak" ng-controller="ctrl" method="post">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">路由规则</h3>
			</div>
			<div class="panel-body">
				<div class="well" ng-repeat="v in data">
					<div class="form-group">
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-12 col-md-11">
								<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
									<span class="input-group-addon">功能描述</span>
									<input class="form-control" ng-model="v.title" type="text" placeholder="描述路由的使用场景">
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
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-12 col-md-11">
								<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
									<span class="input-group-addon">路由规则</span>
									<input class="form-control" ng-model="v.router"
									       placeholder="如: article{siteid}-{aid}-{cid}-{mid}.html" type="text">
								</div>
								<span class="help-block">路由规则必须以模块为前缀</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-12 col-md-11">
								<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
									<span class="input-group-addon">匹配地址</span>
									<input class="form-control" ng-model="v.url"
									       placeholder="如: m=article&action=controller/entry/content&siteid={siteid}&cid={cid}&aid={aid}"
									       type="text">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12 col-xs-12 col-md-12">
						<div class="well text-center" style="cursor: pointer;color:#999;" ng-click="add()" >
							<i class="fa fa-plus-circle fa-4x"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="data" value="@{{data}}">
		<button type="submit" class="btn btn-primary">保存修改</button>
	</form>
</block>
<script>
	require(['angular', 'util', 'underscore', 'jquery'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
				$scope.data = <?php echo $data;?>;
				$scope.add = function () {
					$scope.data.push({domain: ""});
				}
				$scope.del = function (item) {
					$scope.data = _.without($scope.data, item);
				}
			}])
			angular.bootstrap($('form')[0], ['app']);
			util.submit({successUrl: 'refresh'});
		})

	})
</script>