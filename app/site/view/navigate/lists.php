<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li><a href="{{url('site.lists',['m'=>'article'])}}">返回站点列表 </a></li>
		<li class="active">
			<a href="javascript:;"><?=\Navigate::title( );?></a>
		</li>
		<if value="Request::get('m')=='article'">
			<li><a href="{{u('site/navigate/post',['m'=>'article'])}}&webid={{$_GET['webid']}}&entry=home">添加菜单</a></li>
		</if>
	</ul>
	<form action="" method="post" id="form" ng-controller="ctrl" class="form-horizontal ng-cloak" ng-cloak>
		{{csrf_field()}}
		<if value="Request::get('entry')=='home'">
			<div class="panel panel-info">
				<div class="panel-heading">筛选</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-2 control-label">站点</label>
						<div class="col-sm-8">
							<select class="form-control" ng-change="changeWeb()" ng-model="webid" ng-options="a.id as a.title for a in web">
								<option value="">选择微站</option>
							</select>
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
				这里提供了能够显示的导航菜单, 你可以选择性的自定义或显示隐藏, 所有操作更改后需要点击保存按钮才有效。
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
						<if value="Request::get('entry')=='home'">
							<!--模块链接时不显示位置,位置在文章系统有效-->
							<th width="90">位置</th>
						</if>
						<th>是否在微站上显示</th>
						<th width="150">操作</th>
					</tr>
					</thead>
					<tbody>
					<tr ng-repeat="(key,field) in nav">
						<td ng-bind="field.id"></td>
						<td>
							<i ng-click="upFont(field)" ng-if="field.icontype==1" class="@{{field.css.icon}} fa-2x" style="color:@{{field.css.color}}"></i>
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
						<if value="Request::get('entry')=='home'">
							<!--模块链接时不显示位置,位置在文章系统有效-->
							<td>
								<select class="form-control" ng-options="a.position as a.title for a in template.template_position" ng-model="field.position">
									<option value="">不显示</option>
								</select>
							</td>
						</if>
						<td>
							<input type="checkbox" data="@{{key}}" class="bootstrap-switch" ng-checked="field.status==1">
						</td>
						<td ng-if="field.id">
							<div class="btn-group">
								<a href="?s=site/navigate/post&m=article&webid={{Request::get('webid')}}&entry={{$_GET['m']}}&id=@{{field.id}}" class="btn btn-default">
									编辑
								</a>
								<a href="javascript:;" ng-click="del(field.id)" class="btn btn-default">删除</a>
							</div>
						</td>
						<td ng-if="!field.id"></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<input type="hidden" name="data">
		<button type="submit" class="btn btn-primary">保存修改</button>
	</form>
</block>
<style>
	table > tbody > tr > td {
		overflow : visible;
	}
</style>
<script>
	require(['util', 'angular', 'underscore','hdcms'], function (util, angular, _,hdcms) {
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
				location.replace("?s=site/navigate/lists&entry=home&m=article&webid=" + $scope.webid);
			}
			//选择链接
			$scope.url = {
				//获取系统链接
				linkBrowsers: function (field) {
					hdcms.link.system(function (link) {
						field.url = link;
						$scope.$apply();
					});
				}
			}
			//删除菜单
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
			//选择系统图标
			$scope.upFont = function (item) {
				util.font(function (icon) {
					item.css.icon = icon;
					$scope.$apply();
				});
			};
			//更改状态
			require(['bootstrap.switch', 'util'], function ($, util) {
				$(".bootstrap-switch").bootstrapSwitch();
				$('.bootstrap-switch').on('switchChange.bootstrapSwitch', function (event, state) {
					var data = $scope.nav[$(this).attr('data')];
					if (!data)return;
					data.status = state ? 1 : 0;
				});
			});
		}]);
		$(function () {
			angular.bootstrap(document.getElementById('form'), ['app']);
		});
	});

</script>