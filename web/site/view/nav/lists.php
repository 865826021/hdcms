<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li><a href="{{site_url('article/manage/site')}}">返回站点列表</a></li>
		<li class="active">
			<a href="javascript:;"><?php echo m( 'WebNav' )->getEntryTitle( q( 'get.entry' ) ); ?>菜单</a>
		</li>
		<if value="empty($_GET['m'])">
			<li><a href="{{u('site/nav/post')}}&webid={{$_GET['webid']}}&entry={{$_GET['entry']}}">添加菜单</a></li>
		</if>
	</ul>
	<form action="" method="post" id="form" ng-controller="ctrl" class="form-horizontal ng-cloak" ng-cloak>
		<if value="q('get.entry')=='home'">
			<div class="panel panel-info">
				<div class="panel-heading">
					筛选
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-2 control-label">站点</label>
						<div class="col-sm-8">
							<select class="form-control" ng-change="changeWeb()" ng-model="webid" ng-options="a.id as a.title for a in web"></select>
						</div>
					</div>
				</div>
			</div>
			<div class="alert alert-info">
				<div ng-show="template.position">
					当前使用的风格为：@{{template.title}}，模板目录：theme/@{{template.name}}。此模板提供 @{{template.position}} 个导航位置，您可以指定导航在特定的位置显示，未指位置的导航将无法显示
				</div>
				<div ng-hide="template.position">
					当前使用的风格为：@{{template.title}}，模板目录：theme/@{{template.name}}。此模板未提供导航位置
				</div>
			</div>
		</if>
		<div class="panel panel-default">
			<div class="panel-heading">
				这里提供了能够显示的导航菜单, 你可以选择性的自定义或显示隐藏
			</div>
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
					<tr>
						<th width="50">编号</th>
						<th>图标</th>
						<th>标题</th>
						<th width="250">链接</th>
						<th width="120">排序</th>
						<th width="90">位置</th>
						<th>是否在微站上显示</th>
						<th width="100">操作</th>
					</tr>
					</thead>
					<tbody>
					<tr ng-repeat="(key,field) in nav">
						<td ng-bind="field.id"></td>
						<td>
							<i ng-if="field.icontype==1" class="@{{field.css.icon}} fa-2x" style="color:@{{field.css.color}}"></i>
							<img ng-if="field.icontype==2" ng-src="@{{field.css.image}}" style="width:35px;">
						</td>
						<td>
							<input type="text" class="form-control" ng-model="field.name">
						</td>
						<td>
							<div class="input-group">
								<input type="text" class="form-control" ng-model="field.url">
								<div class="input-group-btn">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
									        aria-expanded="false">选择链接 <span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="javascript:;" ng-click="url.linkBrowsers(field)">系统菜单</a></li>
									</ul>
								</div>
							</div>
						</td>
						<td>
							<input type="text" class="form-control" ng-model="field.orderby"/>
						</td>
						<td>
							<select class="form-control" ng-options="a.position as a.title for a in template.template_position"
							        ng-model="field.position">
								<option value="">不显示</option>
							</select>
						</td>
						<td>
							<input type="checkbox" data="@{{key}}" class="bootstrap-switch" ng-checked="field.status==1">
						</td>
						<td ng-if="field.id">
							<a href="?s=site/nav/post&webid={{$_GET['webid']}}&entry=@{{field.entry}}&id=@{{field.id}}&m=@{{field.module}}">编辑</a> -
							<a href="javascript:;" ng-click="del(field.id)">删除</a>
						</td>
						<td ng-if="!field.id"></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<input type="hidden" name="data">
		<button type="submit" class="btn btn-primary">确定</button>
	</form>
</block>
<style>
	table > tbody > tr > td {
		overflow : visible;
	}
</style>
<script>
	require(['util', 'angular', 'underscore'], function (util, angular, _) {
		angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
			$scope.webid = <?php echo $webid;?>;
			$scope.web = <?php echo json_encode( $web );?>;
			$scope.template =<?php echo json_encode( $template );?>;
			$scope.nav =<?php echo json_encode( $nav );?>;
			var position = [];
			for (var i = 1; i <= $scope.template.position; i++) {
				position.push({position: i, title: '位置' + i});
			}
			$scope.template.template_position = position;

			$('form').submit(function () {
				$("[name='data']").val(angular.toJson($scope.nav));
			})

			//选择站点
			$scope.changeWeb = function () {
				location.replace("?s=site/nav/lists&entry={{$_GET['entry']}}&m={{$_GET['m']}}&webid=" + $scope.webid);
			}
			//选择链接
			$scope.url = {
				//获取系统链接
				linkBrowsers: function (field) {
					util.linkBrowser(function (link) {
						field.url = link;
						$scope.$apply();
					});
				}
			}
			$scope.del = function (id) {
				util.confirm('确定删除菜单吗?', function () {
					var nav = $scope.nav;
					$.get('?s=site/nav/del', {id: id}, function (res) {
						if (res.valid) {
							util.message(res.message, 'refresh', 'success')
						} else {
							util.message(res.message, '', 'error');
						}
					}, 'json');
				})
			}
			//更改状态
			require(['bootstrap.switch', 'util'], function ($, util) {
				$(".bootstrap-switch").bootstrapSwitch();
				$('.bootstrap-switch').on('switchChange.bootstrapSwitch', function (event, state) {
					var data = $scope.nav[$(this).attr('data')];
					if (!data)return;
					data.status = state ? 1 : 0;
					$.post("?s=site/nav/changeStatus", {data: data}, function (res) {
						if (res.valid == 0) {
							util.message(res.message, '', 'error');
						} else {
							window.setTimeout(function () {
								location.reload(true);
							}, 300);
						}
					}, 'json');
				});
			});
		}]);
		$(function () {
			angular.bootstrap(document.getElementById('form'), ['app']);
		});
	});

</script>