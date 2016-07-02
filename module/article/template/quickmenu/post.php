<extend file="resource/view/site"/>
<link rel="stylesheet" href="module/article/template/quickmenu/css.css">
<link rel="stylesheet" href="ucenter/css/quickmenu.css">
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li><a href="?a=ucenter/post&t=site&m=article">微站会员中心</a></li>
		<li class="active"><a href="javascript:;">微站快捷导航</a></li>
	</ul>
	<form action="" method="post" id="form" class="ng-cloak form-horizontal ng-cloak" ng-cloak ng-controller="commonCtrl" ng-submit="submit()">
		<div class="well clearfix">
			<div class="col-sm-8">
				<h4><strong>快捷导航</strong></h4>
				<p>微站的各个页面可以通过导航串联起来。通过精心设置的导航，方便访问者在页面或是栏目间快速切换，引导访问者前往您期望的页面。</p>
			</div>
			<div class="col-sm-4 text-right">
				<input type="checkbox" name="status" value="1" class="bootstrap-switch" ng-checked="menu.status">
			</div>
		</div>
		<div class="quickmenu clearfix">
			<div class="mobile_view col-sm-4">
				<div class="mobile-header text-center">
					<img src="resource/images/mobile_head_t.png" style="margin-top: 20px;">
				</div>
				<div class="mobile-body">
					<img src="resource/images/mobile-header.png" style="width: 100%;">
					<!--菜单显示区域-->
					<div class="menu_html" ng-init="tpl=menu.params.style+'_display.html'" ng-include="tpl"></div>
				</div>
				<div class="mobile-footer">
					<div class="home-btn"></div>
				</div>
			</div>
			<div class="slide col-sm-6" style="margin: 80px 0px 0px 10px;">
				<div class="well">
					将导航应用在以下页面:
					<div style="margin-top: 10px;">
						<label class="checkbox-inline"><input type="checkbox" ng-true-value="1" ng-model="menu.params.has_ucenter"> 会员中心</label>
						<label class="checkbox-inline"><input type="checkbox" ng-true-value="1" ng-model="menu.params.has_home"> 微站主页</label>
						<label class="checkbox-inline"><input type="checkbox" ng-true-value="1" ng-model="menu.params.has_special"> 专题页</label>
						<label class="checkbox-inline"><input type="checkbox" ng-true-value="1" ng-model="menu.params.has_article"> 文章及分类</label>
					</div>
					<div style="margin-top: 10px;">
						将导航隐藏在以下模块:
						<a href="javascript:;" ng-click="moduleBrowsers(this)">选择模块</a>
						<div style="margin-top: 10px;">
							<span class="label label-success" ng-repeat="m in menu.params.modules" ng-bind="m.title"
							      style="margin: 0px 5px 5px 0px;display: inline-block;"></span>
							<span class="label label-warning" ng-show="menu.params.modules.length==0">未设置，将在全部模块中显示</span>
						</div>
					</div>
				</div>
				<div class="well menus_setting">
					<div class="arrow-left"></div>
					<div class="page-header clearfix">
						当前模板: 微信公众号自定义菜单模板
						<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">选择模板</button>
					</div>
					<!--菜单设置-->
					<div ng-init="tplEdit=menu.params.style+'_edit.html'" ng-include="tplEdit"></div>
				</div>
			</div>
		</div>
		<!--选择风格模板-->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">选择导航模板</h4>
					</div>
					<div class="modal-body">
						<div class="alert">
							<label class="radio-inline">
								<input type="radio" value="quickmenu_normal" ng-model="menu.params.style" > 仿微信菜单模板
							</label>
							<div class="quickmenu_normal_img"></div>
						</div>
						<div class="alert">
							<label class="radio-inline">
								<input type="radio" value="quickmenu_shop" ng-model="menu.params.style" > 商城导航模板
							</label>
							<div class="quickmenu_shop_img"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
						<button type="button" class="btn btn-primary" ng-click="select_style()">确定</button>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<input type="hidden" name="data">
		<div class="text-center">
			<button type="submit" class="btn btn-primary">保存菜单</button>
			<button type="button" class="btn btn-danger" onclick="location.reload(true)">放弃操作</button>
		</div>
	</form>

</block>
<style>
	/*菜单样式一的二级菜单默认显示*/
	.quickmenu_normal{
		position: absolute;
	}
	.quickmenu_normal .sub-menus {
		display : block !important;
	}
</style>
<script>
	//阻止菜单的点击事件
	$(function () {
		$('.quickmenu').delegate('a', 'click', function () {
			return false;
		});
	})
	require(['../../js/quickmenu'], function () {
		$(function () {
			menu =<?php echo json_encode( $field );?>;
			angular.bootstrap(document.getElementById('form'), ['app']);
		});
	});
</script>