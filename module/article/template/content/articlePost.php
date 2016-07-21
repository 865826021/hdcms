<extend file="resource/view/site"/>
<block name="content">
	<form action="" method="post" class="form-horizontal ng-cloak" ng-cloak id="form" ng-controller="MyController">
		<ul class="nav nav-tabs" role="tablist">
			<li><a href="{{site_url('content/article')}}">文章管理</a></li>
			<if value="q('get.aid')">
				<li><a href="?a=article/content/articlePost&t=site&cid=0">发表文章</a></li>
				<li class="active"><a href="javascript:;">编辑文章</a></li>
				<else/>
				<li class="active"><a href="?a=article/content/articlePost&t=site&cid=0">发表文章</a></li>
			</if>
		</ul>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">文章管理</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">排序</label>
					<div class="col-sm-9">
						<input type="number" class="form-control" ng-model="field.orderby">
						<span class="help-block">文章的显示顺序，越大则越靠前</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label star">标题</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.title" required="required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label star">文章类别</label>
					<div class="col-sm-9">
						<select class="js-example-basic-single form-control" ng-model="field.category_cid"
						        ng-options="a.cid as a._title for a in category">
							<option value="">选择栏目</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">文章触发关键字</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.keyword" ng-blur="checkWxKeyword($event,field.rid)">
						<span class="help-block keyword_error"></span>
						<span class="help-block">添加关键字以后,系统将生成一条图文规则,用户可以通过输入关键字来阅读文章。多个关键字请用英文“,”隔开</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">自定义属性</label>
					<div class="col-sm-9">
						<label class="checkbox-inline">
							<input type="checkbox" ng-true-value="1" ng-model="field.ishot"> 头条
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" ng-true-value="1" ng-model="field.iscommend"> 推荐
						</label>
						<span class="help-block">添加关键字以后,系统将生成一条图文规则,用户可以通过输入关键字来阅读文章。多个关键字请用英文“,”隔开</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">文章来源</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.source">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">文章作者</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.author">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">缩略图</label>
					<div class="col-sm-9">
						<div class="input-group">
							<input type="text" class="form-control" ng-model="field.thumb">
							<div class="input-group-btn">
								<button ng-click="upImage()" class="btn btn-default" type="button">选择图片</button>
							</div>
						</div>
						<div class="input-group" style="margin-top:5px;" ng-if="field.thumb">
							<img ng-src="@{{field.thumb}}" class="img-responsive img-thumbnail" width="150">
							<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" ng-click="removeImg()">×</em>
						</div>
						<span class="help-block">封面（大图片建议尺寸：360像素 * 200像素）</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">选择内容模板</label>
					<div class="col-sm-9">
						<select class="js-example-basic-single form-control" ng-model="field.template_name"
						        ng-options="a.name as a.title for a in template">
							<option value="">选择模板风格</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">简介</label>
					<div class="col-sm-9">
						<textarea name="description" rows="4" class="form-control" ng-model="field.description"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label star">内容</label>
					<div class="col-sm-9">
						<textarea id="container" style="height:300px;width:100%;"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">直接链接</label>
					<div class="col-sm-9">
						<div class="input-group">
							<input type="text" class="form-control" aria-label="..." ng-model="field.linkurl">
							<div class="input-group-btn">
								<button type="button" name="linkurl" class="btn btn-default dropdown-toggle"
								        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">选择链接 <span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="javascript:;" ng-click="link.system()">系统菜单</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">阅读次数</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" ng-model="field.click">
						<span class="help-block">默认为0。您可以设置一个初始值,阅读次数会在该初始值上增加。</span>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="data">
		<button class="btn btn-primary" type="submit">确定</button>
	</form>
</block>

<script>
	require(['angular', 'util'], function (angular, util) {
		angular.module('myApp', []).controller('MyController', ['$scope', function ($scope) {
			$scope.field =<?php echo json_encode( $field );?>;
			$scope.template =<?php echo json_encode( $template );?>;
			$scope.category =<?php echo json_encode( $category );?>;
			$scope.link = {
				system: function () {
					util.linkBrowser(function (link) {
						$scope.field.linkurl = link;
						$scope.$apply();
					});
				}
			};
			//选择缩略图
			$scope.upImage = function () {
				require(['util'], function (util) {
					util.image(function (images) {
						$scope.field.thumb = images[0];
						$scope.$apply();
					})
				});
			};
			//移除图片 
			$scope.removeImg = function () {
				$scope.field.thumb = '';
			}
			//图文编辑器
			util.ueditor('container', {}, function (editor) {
				editor.addListener('contentChange', function () {
					$scope.field.content = editor.getContent();
					$scope.$apply();
				});
				editor.addListener('ready', function () {
					if (editor && editor.getContent() != $scope.field.content) {
						editor.setContent($scope.field.content);
					}
					$scope.$watch('field.content', function (value) {
						if (editor && editor.getContent() != value) {
							editor.setContent(value ? value : '');
						}
					});
				});
				editor.addListener('clearRange', function () {
					$scope.field.content = editor.getContent();
					$scope.$apply();
				});
			});
			//验证关键词
			$scope.checkWxKeyword = function (e, rid) {
				util.checkWxKeyword(e.target, $scope.field.rid, function (res) {
					$scope.$apply();
				});
			}
			$('form').submit(function () {
				var msg = '';
				if (!$scope.field.content) {
					msg += '内容不能为空<br/>';
				}
				if (!$scope.field.category_cid) {
					msg += '请选择文章栏目<br/>';
				}
				if ($.trim($(".keyword_error").text())) {
					msg += '关键词已经被使用<br/>';
				}
				if (msg) {
					util.message(msg, '', 'error');
					return false;
				}
				$("[name='data']").val(angular.toJson($scope.field));
			});
		}]);
		$(function () {
			angular.bootstrap(document.getElementById('form'), ['myApp'])
		});
	});
</script>
