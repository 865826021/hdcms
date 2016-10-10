<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li><a href="{{site_url('manage/site','','article')}}">返回站点列表 </a></li>
		<li><a href="javascript:history.back();">导航菜单列表</a></li>
		<li class="active"><a href="javascript:;">添加导航菜单</a></li>
	</ul>
	<form action="" method="post" class="form-horizontal ng-cloak" id="form" ng-controller="MyController" ng-cloak>
		<input type="hidden" name="__HISTORY__" value="{{__HISTORY__}}">
		<div class="panel panel-default">
			<div class="panel-heading">微站导航菜单</div>
			<div class="panel-body">
				<if value="q('get.entry')=='home'">
					<div class="form-group">
						<label class="col-sm-2 control-label">分配到微站</label>
						<div class="col-sm-8">
							<select class="form-control" ng-model="field.web_id" ng-options="a.id as a.title for a in web">
								<option value="">选择站点</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">导航显示位置</label>
						<div class="col-sm-8">
							<select class="form-control" ng-model="field.position" ng-options="a.position as a.title for a in position_data">
								<option value="">不显示</option>
							</select>
                        <span class="help-block">
                            设置位置后可以将导航菜单显示到模板对应的位置中。（可以同时设置多个导航在同一个位置中，会根据排序大小依次显示），显示的位置必须要有模板支持。
                        </span>
						</div>
					</div>
				</if>
				<div class="form-group">
					<label class="col-sm-2 control-label star">导航名称</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" ng-model="field.name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">描述</label>
					<div class="col-sm-8">
						<textarea class="form-control" rows="5" ng-model="field.description"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">状态</label>
					<div class="col-sm-8">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.status" value="1"> 显示
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.status" value="0"> 隐藏
						</label>
						<span class="help-block">设置导航菜单的显示状态</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label star">直接链接</label>
					<div class="col-sm-8">
						<div class="input-group">
							<input type="text" class="form-control" ng-model="field.url">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
								        aria-expanded="false">选择链接 <span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="javascript:;" ng-click="getUrl.systemLink()">系统菜单</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">排序</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" ng-model="field.orderby">
						<span class="help-block">导航排序，越大越靠前</span>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				导航样式
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">图标类型</label>
					<div class="col-sm-9">
						<label class="radio-inline">
							<input type="radio" ng-model="field.icontype" value="1"> 系统内置
						</label>
						<label class="radio-inline">
							<input type="radio" ng-model="field.icontype" value="2"> 自定义上传
						</label>
					</div>
				</div>
				<div id="icon" ng-show="field.icontype==1">
					<div class="form-group">
						<label class="col-sm-2 control-label">系统图标</label>
						<div class="col-sm-9">
							<div class="input-group" style="width: 300px;">
								<input type="text" class="form-control iconfontinput" ng-model="field.css.icon">
                                <span class="input-group-addon iconfontspan" style="border-left: none">
                                    <i class="@{{field.css.icon}}"></i>
                                </span>
                              <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="upFont()">选择图标</button>
                              </span>
							</div>
							<span class="help-block">导航的背景图标，系统提供了丰富的图标ICON。</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图标颜色</label>
						<div class="col-sm-9">
							<div class="input-group" style="width: 340px;">
								<input type="text" class="form-control" ng-model="field.css.color">
								<span class="input-group-addon" id="basic-addon1"
								      ng-style="{width: '40px','border-left':'none',background:field.css.color}"></span>
								<div class="input-group-btn">
									<button type="button" class="btn btn-default" id="getColor">选择颜色</button>
									<button type="button" class="btn btn-default"><i class="fa fa-remove"></i></button>
								</div>
							</div>
							<span class="help-block">图标颜色，上传图标时此设置项无效</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">图标大小</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="text" class="form-control" value="35" ng-model="field.css.size">
								<span class="input-group-addon">px</span>
							</div>
							<span class="help-block">图标的尺寸大小，单位为像素，上传图标时此设置项无效</span>
						</div>
					</div>
				</div>
				<div id="iconimg" ng-if="field.icontype==2">
					<div class="form-group">
						<label class="col-sm-2 control-label">上传图标</label>
						<div class="col-sm-5">
							<div class="input-group">
								<input type="text" class="form-control" ng-model="field.css.image">
								<div class="input-group-btn">
									<button ng-click="upImageIcon()" class="btn btn-default" type="button">选择图片</button>
								</div>
							</div>
							<div class="input-group" style="margin-top:5px;" ng-if="field.css.image">
								<img ng-src="@{{field.css.image}}" class="img-responsive img-thumbnail iconimg" width="150">
								<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片"
								    ng-click="removeImageIcon()">×</em>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="data">
		<button type="submit" class="btn btn-primary">保存</button>
	</form>

</block>

<script>
	require(['angular', 'util'], function (angular, util) {
		angular.module('myApp', []).controller('MyController', ['$scope', function ($scope) {
			$scope.web = <?php echo json_encode( $web,JSON_UNESCAPED_UNICODE );?>;
			$scope.field = <?php echo json_encode( $field,JSON_UNESCAPED_UNICODE );?>;

			//设置导航显示位置
			$scope.getTemplatePositon = function () {
				var position = [];
				var len = 0;
				//编辑站点时根据当前导航所在站点显示站点位置
				for (var i = 0; i < $scope.web.length; i++) {
					if ($scope.field.web_id == $scope.web[i].id) {
						len = $scope.web[i].position;
					}
				}
				for (var i = 1; i <= len; i++) {
					position.push({position: i, title: '位置' + i});
				}
				$scope.position_data = position;
			}
			$scope.getTemplatePositon();
			//监测站点更改来获取导航显示位置
			$scope.$watch('field.web_id', function (newValue, newOld) {
				$scope.getTemplatePositon();
			});
			//选择链接
			$scope.getUrl = {
				systemLink: function () {
					util.linkBrowser(function (link) {
						$scope.field.url = link;
						$scope.$apply();
					});
				}
			}
			//选择系统图标
			$scope.upFont = function () {
				util.font(function (icon) {
					$scope.field.css.icon = icon;
					$scope.$apply();
				});
			};
			//选择上传图片图标
			$scope.upImageIcon = function () {
				util.image(function (images) {
					$scope.field.css.image = images[0];
					$scope.$apply();
				})
			}
			//删除图片图标
			$scope.removeImageIcon = function () {
				$scope.field.css.image = '';
			}
			//获取图标颜色
			util.colorpicker({
				element: '#getColor',//点击元素id
				//回调函数
				callback: function (color) {
					$scope.field.css.color = color;
					$scope.$apply();
				}
			});
			//提交表单
			$('form').submit(function () {
				var msg = '';
				if (!$scope.field.name) {
					msg += '导航名称不能为空<br/>';
				}
				if (!$scope.field.url) {
					msg += '跳转链接不能为空<br/>';
				}
				if ($scope.field.icontype == 2 && !$scope.field.css.image) {
					msg += '请选择上传图标';
				}
				if (msg) {
					util.message(msg, '', 'error');
					return false;
				}
				$('[name="data"]').val(angular.toJson($scope.field));
			})
		}]);
		angular.bootstrap(document.getElementById('form'), ['myApp'])
	});
</script>