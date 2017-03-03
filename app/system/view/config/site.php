<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">站点设置</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="javascript:;">站点选项</a></li>
	</ul>

	<form action="" class="form-horizontal ng-cloak" ng-cloak method="post" id="form" ng-controller="myController">
		{{csrf_field()}}
		<div class="panel panel-default">
			<div class="panel-heading">
				基本设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">开启站点</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.is_open"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.is_open"> 否
						</label>
					</div>
				</div>
				<div class="form-group" ng-show="field.is_open==0">
					<label class="col-sm-2 control-label">关闭原因</label>

					<div class="col-sm-6">
						<textarea class="form-control" ng-model="field.close_message" rows="6"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">开启登录验证码</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.enable_code"> 是
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.enable_code"> 否
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				上传配置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">上传大小</label>

					<div class="col-sm-5">
						<div class="input-group">
							<input type="text" class="form-control" ng-model="field.upload.size">
							<span class="input-group-addon">KB</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">上传类型</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" ng-model="field.upload.type">
						<span class="help-block">请用英文半角逗号分隔文件类型</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">上传类型</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="local" ng-model="field.upload.mold"> 本地上传
						</label>
						<label class="radio-inline">
							<input type="radio" value="oss" ng-model="field.upload.mold"> 阿里云OSS
						</label>
					</div>
				</div>
				<div class="well">
					<div class="form-group" ng-if="field.upload.mold=='local'">
						<label for="" class="col-sm-2 control-label">上传目录</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="field.upload.path">
							<span class="help-block">上传到本地服务器的目录名称</span>
						</div>
					</div>
					<div class="form-group" ng-if="field.upload.mold=='oss'">
						<label for="" class="col-sm-2 control-label">accessKeyId</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="field.oss.accessKeyId">
							<span class="help-block">登录阿里云访问控制查看 https://ram.console.aliyun.com/</span>
						</div>
					</div>
					<div class="form-group" ng-if="field.upload.mold=='oss'">
						<label for="" class="col-sm-2 control-label">accessKeySecret</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="field.oss.accessKeySecret">
							<span class="help-block">登录阿里云访问控制查看 https://ram.console.aliyun.com</span>
						</div>
					</div>
					<div class="form-group" ng-if="field.upload.mold=='oss'">
						<label for="" class="col-sm-2 control-label">bucket 外网域名</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="field.oss.bucket_endpoint">
							<span class="help-block">请登录阿里云后台查看 https://oss.console.aliyun.com/index?spm=5176.2020520101.1002.d10oss.8O7bNi# 主要用于js 结合 PHP生成sign发送使用</span>
						</div>
					</div>
					<div class="form-group" ng-if="field.upload.mold=='oss'">
						<label for="" class="col-sm-2 control-label">公共endpoint</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="field.oss.endpoint">
							<span class="help-block">公共endpoint 请根据Region和Endpoint对照表: https://help.aliyun.com/document_detail/31837.html 选择, 主要用于PHP上传文件使用</span>
						</div>
					</div>
					<div class="form-group" ng-if="field.upload.mold=='oss'">
						<label for="" class="col-sm-2 control-label">bucket</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="field.oss.bucket">
							<span class="help-block">Bucket块名称 https://oss.console.aliyun.com/index</span>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				开发模式
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">调试模式</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.app.debug"> 开启
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.app.debug"> 关闭
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">伪静态</label>

					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" value="1" ng-model="field.http.rewrite"> 开启
						</label>
						<label class="radio-inline">
							<input type="radio" value="0" ng-model="field.http.rewrite"> 关闭
						</label>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="site">
		<button class="btn btn-primary">提交</button>
	</form>
</block>

<script>
	require(['angular', 'util'], function (angular, util) {
		angular.module('myApp', []).controller('myController', ['$scope', function ($scope) {
			$scope.field =<?php echo $field ? json_encode( $field ) : '{"is_open":0,"close_message":"网站维护中,请稍候访问","enable_code":1,"upload":{}}';?>;
			util.submit({
				data:this.data,
				//提交前执行的函数,函数返回true才会提交，可以提交前进行表单验证等处理
				before:function(){
					this.data = {site: angular.toJson($scope.field)};
					return true;
				},
				successUrl:'refresh'
			});
		}]);

		angular.bootstrap(document.getElementById('form'), ['myApp']);
	})
</script>
