<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li><a href="{{site_url('manage/site')}}">微站管理</a></li>
		<if value="q('get.webid')">
			<li><a href="?a=article/manage/SitePost&t=site">添加站点</a></li>
			<li class="active"><a href="#">编辑站点</a></li>
			<else/>
			<li class="active"><a href="#">添加站点</a></li>
		</if>
	</ul>
	<form action="" method="post" id="form" role="form" class="form-horizontal ng-cloak" ng-cloak ng-controller="ctrl">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">站点信息</h3>
			</div>
			<div class="panel-body">
				<if value="q('get.webid')">
					<div class="form-group">
						<label class="col-sm-2 control-label">站点链接</label>
						<div class="col-sm-10">
							<p class="form-control-static">
								{{__ROOT__}}/?a=entry/home&m=article&t=web&webid={{$_GET['webid']}}&t=web
							</p>
						</div>
					</div>
				</if>
				<div class="form-group">
					<label class="col-sm-2 control-label star">站点名称</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.name" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">是否启用</label>
					<div class="col-sm-9">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.status"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.status"> 否
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">选择微站风格</label>
					<div class="col-sm-9">
						<button class="btn btn-success" type="button" ng-click="loadTpl(this)">选择风格</button>
					</div>
				</div>
				<div class="form-group template" ng-show="field.template_tid">
					<label class="col-sm-2 control-label star">当前微站风格</label>
					<div class="col-sm-9 box" ng-if="field.template_tid">
						<div class="thumbnail">
							<h5 ng-bind="field.template_title"></h5>
							<img src="theme/@{{field.template_name}}/@{{field.template_thumb}}">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label star">触发关键字</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" required="required" ng-blur="checkWxKeyword($event)" ng-model="field.keyword"
						       placeholder="请输入微信关键词">
						<span class="help-block keyword_error"></span>
						<span class="help-block">用户触发关键字，系统回复此页面的图文链接</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">封面</label>
					<div class="col-sm-9">
						<div class="input-group">
							<input type="text" class="form-control" readonly="" ng-model="field.thumb">
							<div class="input-group-btn">
								<button ng-click="upImage()" class="btn btn-default" type="button">选择图片</button>
							</div>
						</div>
						<div class="input-group" style="margin-top:5px;">
							<img src="resource/images/nopic.jpg" class="img-responsive img-thumbnail" width="150" ng-hide="field.thumb">
							<img src="@{{field.thumb}}" class="img-responsive img-thumbnail" width="150" ng-show="field.thumb">
						</div>
						<span class="help-block">用于用户触发关键字后，系统回复时的封面图片</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label star">页面描述</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" ng-model="field.description" required="required"></textarea>
						<span class="help-block">用户通过微信分享给朋友时,会自动显示页面描述</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">绑定域名</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.domain">
						<span class="help-block">绑定时请先将域名解析指向到本服务器，请只填写host部分，例如：www.baidu.com</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">底部自定义</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" ng-model="field.footer"></textarea>
						<span class="help-block">自定义底部信息，支持HTML</span>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="data">
		<button class="btn btn-primary">确定</button>
	</form>
</block>
<style>
	.template .thumbnail {
		height   : 270px;
		width    : 180px;
		overflow : hidden;
		float    : left;
		margin   : 3px 7px;
	}

	.template .thumbnail .caption {
		padding : 0px;
	}

	.template .thumbnail h5 {
		font-size   : 14px;
		overflow    : hidden;
		height      : 25px;
		margin      : 3px 0px;
		line-height : 2em;
	}

	.template .thumbnail > img {
		height        : 225px;
		max-width     : 168px;
		border-radius : 3px;
	}

	.template .thumbnail .caption {
		margin-top : 8px;
	}
</style>
<script>
	require(['angular', 'util'], function (angular, util) {
		angular.module('app', []).controller('ctrl', ['$scope', '$http', function ($scope, $http) {
			$scope.field = <?php echo $field ? $field : 'false'?>;
			if (!$scope.field) {
				$scope.field = {rid: 0, status: 1, is_default: 0};
			}
			//上传封面图片
			$scope.upImage = function (obj) {
				require(['util'], function (util) {
					util.image(function (images) {
						$scope.field.thumb = images[0];
						$scope.$apply();
					})
				});
			};
			//验证关键词
			$scope.checkWxKeyword = function (e) {
				util.checkWxKeyword(e.target, $scope.field.rid, function (res) {
					$scope.$apply();
				});
			}
			//提交表单
			$("form").submit(function () {
				$("[name='data']").val(angular.toJson($scope.field));
				var msg = '';
				if (!$scope.field.thumb) {
					msg += "请选择封面图片<br/>";
				}
				if ($(".keyword_error").text()) {
					msg += "关键词已经存在<br/>";
				}
				if (!$scope.field.template_tid) {
					msg += "请选择微站风格<br/>";
				}
				if (msg) {
					util.message(msg, '', 'error');
					return false;
				}
			});
			//加载模板
			$scope.loadTpl = function () {
				var modalobj = util.modal({
					content: ["{{site_url('loadTpl')}}"],
					title: '微站模板风格列表',
					width: 850,
					show: true,
				});
				modalobj.on('hidden.bs.modal', function () {
					modalobj.remove();
				});
				$("body").delegate(".template button", "click", function () {
					var name = $(this).attr('name');
					var title = $(this).attr('title');
					var id = $(this).attr('id');
					var thumb = $(this).attr('thumb');
					modalobj.modal('hide');
					$scope.field.template_tid = id;
					$scope.field.template_title = title;
					$scope.field.template_name = name;
					$scope.field.template_thumb = thumb;
					$scope.$apply();
				});
			}
		}]);
		angular.bootstrap(document.getElementById('form'), ['app']);
	})
</script>
