<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li>
			<a href="?s=system/permission/users&siteid={{$_GET['siteid']}}">帐号操作人员列表</a>
		</li>
		<li class="active">权限设置</li>
	</ol>
	<div class="alert alert-info" role="alert">
		<span class="fa fa-info-circle"></span>
		默认未勾选任何菜单时，用户拥有全部权限。
	</div>
	<form action="" method="post" ng-controller="ctrl" class="ng-cloak" ng-cloak ng-submit=" submit($event)">
		<input type="hidden" name="uid" value="{{$_GET['fromuid']}}">
		<div class="panel panel-default" ng-repeat="v in menusAccess">
			<div class="panel-heading">
				<label><input type="checkbox" ng-checked="d.checked" ng-click="selectAll($event)"> @{{v.title}}</label>
			</div>
			<div class="panel-body">
				<div ng-repeat="m in v._data">
					<p class="col-sm-2 col-md-2 col-lg-2" ng-repeat="d in m._data">
						<label>
							<input type="checkbox" name="system[]" value="@{{d.permission}}" ng-checked="d._status"> @{{d.title}}
						</label>
					</p>
				</div>
			</div>
		</div>
		<!--扩展模块权限-->
		<div class="panel panel-default" ng-repeat="v in moduleAccess">
			<div class="panel-heading">
				<label><input type="checkbox" ng-click="selectAll($event)"> @{{v.module.title}}</label>
			</div>
			<div class="panel-body">
				<div  ng-repeat="t in v['access']">
				<p class="col-sm-2 col-md-2 col-lg-2" ng-repeat="d in t">
					<label>
						<input type="checkbox" name="modules[@{{v.module.name}}][]" value="@{{d.identifying}}" ng-checked="d.status">
						@{{d.title}}
					</label>
				</p>
				</div>
			</div>
		</div>
		<button class="btn btn-primary">保存</button>
	</form>
</block>
<script>
	require(['angular', 'underscore', 'util', 'jquery', 'hdcms'], function (angular, _, util, $, hdcms) {
		angular.module('app', []).controller('ctrl', ['$scope', '$sce', function ($scope, $sce) {
			//系统菜单
			$scope.menusAccess = <?php echo json_encode( $menusAccess, JSON_UNESCAPED_UNICODE );?>;
			$scope.moduleAccess = <?php echo json_encode( $moduleAccess, JSON_UNESCAPED_UNICODE );?>;
			$scope.selectAll = function (obj) {
				var elem = $(obj.currentTarget);
				var status = elem.is(":checked");
				elem.parent().parent().next().find('input').prop('checked', status);
			}
			$scope.submit=function($event){
			    $event.preventDefault();
                util.submit({successUrl:'refresh'});
                return false;
            }
		}]);
		$(function () {
			angular.bootstrap($("form")[0], ['app']);
		});
	});

</script>