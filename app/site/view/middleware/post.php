<extend file='resource/view/site.php'/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li class="active"><a href="javascript:;">中间件设置</a></li>
	</ul>

	<form action="" method="post" class="form-horizontal ng-cloak" ng-controller="ctrl" ng-cloak>
		<div class="panel panel-default">
			<div class="panel-heading">
				视频列表
			</div>
			<div class="panel-body">
				<div ng-repeat="v in data">
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">参数设置</label>
						<div class="col-sm-10">
							<div class="col-xs-12 col-sm-12 col-md-4">
								<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
									<span class="input-group-addon">操作描述</span>
									<input class="form-control ng-pristine ng-untouched ng-valid" ng-model="v.title"
									       type="text" placeholder="请输入中文操作名称">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4">
								<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
									<span class="input-group-addon">中间件</span>
									<select name="" id="" class="form-control">
										<option value="">选择中间件</option>
										<option value="upload_begin" ng-selected="v.name=='upload_begin'">上传文件前</option>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-3">
								<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
									<span class="input-group-addon">处理类</span>
									<input class="form-control ng-pristine ng-untouched ng-valid" ng-model="v.middleware"
									       type="text" placeholder="请输入中间件处理类">
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
				</div>
				<div class="form-group">
					<div class="col-sm-10 col-xs-12 col-md-9 col-md-offset-1">
						<div class="well well-sm">
							<a href="javascript:;" ng-click="add()">
								添加中间件 <i class="fa fa-plus-circle" title="添加中间件"></i>
							</a>
						</div>
						<span class="help-block">当前中间件指影响当前模块操作,添加中间件一定要慎重。</span>
					</div>
				</div>
			</div>
		</div>
		<input type="submit" value="保存数据" class="btn btn-primary">
	</form>
</block>
<script>
	require(['angular', 'underscore', 'util', 'jquery', 'hdcms'], function (angular, _, util, $, hdcms) {
		angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
			$scope.data = <?php echo $data;?>;
			$scope.add=function(){
				$scope.data.push({title:'',name:'upload_begin','middleware':''});
			}
			$scope.del=function(item){
				$scope.data = _.without($scope.data,item);
			}
			util.submit({data:this.data,successUrl:'refresh',before:function(){
				this.data = {data:angular.toJson($scope.data)};
				return true;
			}})
		}]);
		$(function () {
			angular.bootstrap($("form")[0], ['app']);
		});
	});

</script>