<extend file="resource/view/site"/>
<block name="content">
	<form action="" method="post" class="form-horizontal ng-cloak" ng-cloak id="form" ng-controller="MyController">
		<ul class="nav nav-tabs" role="tablist">
			<li><a href="?a=content/category&t=site&m=article">栏目管理</a></li>
			<li class="active" ng-if="field.cid>0"><a href="javascript:;">编辑栏目</a></li>
			<li ng-class="{'active':!field.cid}"><a href="?a=content/categoryPost&t=site&m=article">添加栏目</a></li>
		</ul>
		<input type="hidden" name="cid" ng-model="field.cid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">分类详细设置</h3>
			</div>
			<div class="panel-body">
				<div class="form-group" ng-if="field.cid">
					<label class="col-sm-2 control-label">访问地址</label>
					<div class="col-sm-9">
						<div class="form-control-static">
							<a href="{{__ROOT__}}/index.php?a=entry/category&t=web&siteid={{v('site.siteid')}}&cid=@{{field.cid}}&mobile=1&m={{v('module.name')}}"
							   target="_blank">
								{{__ROOT__}}/index.php?a=entry/category&t=web&siteid={{v('site.siteid')}}&cid=@{{field.cid}}&mobile=1&m={{v('module.name')}}
							</a>
							<span class="help-block">您可以根据此地址，添加回复规则，设置访问。</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">排序</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.orderby">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label star">分类名称</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.title" required="required">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">父级栏目</label>
					<div class="col-sm-9">
						<select class="js-example-basic-single form-control" ng-model="field.pid" ng-options="a.cid as a._title for a in category">
							<option value="">一级栏目</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">分类描述</label>
					<div class="col-sm-9">
						<textarea name="description" rows="5" class="form-control" ng-model="field.description"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">是否添加微站首页导航</label>
					<div class="col-sm-9">
						<label class="radio-inline">
							<input type="radio" ng-model="field.isnav" value="1"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" ng-model="field.isnav" value="0"> 否
						</label>
						<span class="help-block">开启此选项后,系统在微站首页导航自动生成以分类名称为导航名称的记录.关闭此选项后,系统将删除对应的导航记录</span>

					</div>
				</div>
				<div class="form-group" ng-show="field.isnav==1">
					<label for="" class="col-sm-2 control-label">分配到微站</label>
					<div class="col-sm-9">
						<select class="form-control" ng-options="a.id as a.title for a in web" ng-model="field.web_id">
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">是否为封面栏目</label>
					<div class="col-sm-9">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.ishomepage" value="1"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.ishomepage" value="0"> 否
						</label>
						<span class="help-block">注意：分类模板将直接引用首页模板 article_index.html</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">选择微站风格</label>
					<div class="col-sm-9">
						<button class="btn btn-success" type="button" ng-click="loadTpl()">选择风格</button>
					</div>
				</div>
				<div class="form-group template current_style" ng-show="field.template_name">
					<label class="col-sm-2 control-label">当前微站风格</label>
					<div class="col-sm-9 box">
						<div class="thumbnail">
							<input type="hidden" name="template_tid" ng-model="field.template_tid"/>
							<h5 ng-bind="template.title">{{$field['template']['title']}}</h5>
							<img ng-src="theme/@{{template.name}}/@{{template.thumb}}">
							<a href="javascript:;" ng-click="field.template_name=''"><i class="fa fa-times-circle"></i></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">直接链接</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.linkurl">
						<span class="help-block">链接必须是以http://或是https://开头。没有直接链接，请留空</span>
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
		<button class="btn btn-primary" type="submit">确定</button>
	</form>
</block>
<style>
	.template .thumbnail {
		height   : 270px;
		width    : 180px;
		overflow : hidden;
		float    : left;
		margin   : 3px 7px;
		position: relative;
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
	.template .thumbnail a{
		position: absolute;
		right:10px;top:10px;
	}

</style>
<script>
	require(['angular'], function (angular) {
		angular.module('myApp', []).controller('MyController', ['$scope', function ($scope) {
			$scope.web = <?php echo json_encode( $web );?>;
			$scope.category = <?php echo json_encode( $category );?>;
			$scope.field = <?php echo json_encode( $field );?>;
			$scope.template = <?php echo json_encode( $template );?>;

			//选择系统图标
			$scope.upFont = function () {
				util.font(function (icon) {
					$scope.field.css.icon = icon;
					$scope.$apply();
				});
			};
			//加载模板
			$scope.loadTpl = function () {
				require(['util'], function (util) {
					var modalobj = util.modal({
						content: ["{{site_url('manage/loadTpl')}}"],
						title: '微站模板风格列表',
						width: 850,
						show: true,
					});
					modalobj.on('hidden.bs.modal', function () {
						modalobj.remove();
					});
					//选择模板
					$("body").delegate(".template button", "click", function () {
						var name = $(this).attr('name');
						var title = $(this).attr('title');
						var id = $(this).attr('id');
						var thumb = $(this).attr('thumb');
						$scope.field.template_name = name;
						$scope.template.title = title;
						$scope.template.name = name;
						$scope.template.thumb = thumb;
						modalobj.modal('hide');
						$scope.$apply();
					});
				});
			}
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
		$(function () {
			angular.bootstrap(document.getElementById('form'), ['myApp'])
		});
	});


</script>