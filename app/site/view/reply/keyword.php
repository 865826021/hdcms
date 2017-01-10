<input type="hidden" ng-model="item.rid">
<div class="form-group" ng-show="advSetting">
	<label class="col-sm-2 control-label">状态</label>
	<div class="col-sm-10">
		<div class="radio">
			<label>
				<input type="radio" value="1" ng-model="rule.status" name="status" ng-checked="rule.status==1">启用
			</label>
			<label>
				<input type="radio" value="0" ng-model="rule.status" name="status" ng-checked="rule.status==0">禁用
			</label>
			<span class="help-block">您可以临时禁用这条回复.</span>
		</div>
	</div>
</div>
<div class="form-group" ng-show="advSetting">
	<label class="col-sm-2 control-label">置顶回复</label>
	<div class="col-sm-10">
		<div class="radio">
			<label>
				<input type="radio" ng-model="rule.istop" name="istop" value="1">置顶
			</label>
			<label>
				<input type="radio" ng-model="rule.istop" name="istop" value="0">普通
			</label>
			<span class="help-block">“置顶”时无论在什么情况下均能触发且使终保持最优先级.</span>
		</div>
	</div>
</div>
<div class="form-group" ng-show="advSetting">
	<label class="col-sm-2 control-label">优先级</label>
	<div class="col-sm-10">
		<input type="text" name="rank" class="form-control" ng-model="rule.rank">
		<span class="help-block">规则优先级，越大则越靠前，最大不得超过254</span>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">触发关键字</label>
	<div class="col-sm-7 col-md-8" ng-repeat="key in rule.keyword" ng-if="key.type==1">
		<input type="text" class="form-control" id="keywordInput" ng-model="key.content"
		       ng-blur="checkWxKeyword($event)">
		<span class="help-block has_keyword"></span>
		<span class="help-block">
            当用户的对话内容符合以上的关键字定义时，会触发这个回复定义。多个关键字请使用逗号隔开。
            <a href="javascript:;" id="keywordEmotion">
	            <i class="fa fa-github-alt"></i> 表情
            </a>
            选择高级触发: 将会提供一系列的高级触发方式供专业用户使用(注意: 如果你不了解, 请不要使用).
        </span>
	</div>
	<div class="col-sm-3 col-md-2">
		<div class="checkbox">
			<label>
				<input type="checkbox" value="1" ng-model="advancedTriggering" ng-checked="advancedTriggering">高级触发
			</label>
		</div>
	</div>
</div>
<div class="form-group" ng-show="advancedTriggering">
	<label class="col-sm-2 control-label">高级触发列表</label>
	<div class="col-sm-10">
		<div class="panel panel-default tab-content">
			<div class="panel-heading">
				<ul class="nav nav-pills" role="tablist">
					<li role="presentation" class="active"><a href="#contain" aria-controls="contain" role="tab"
					                                          data-toggle="tab">包含关键词</a></li>
					<li role="presentation"><a href="#regexp" aria-controls="regexp" role="tab" data-toggle="tab">正则表达式模式匹配</a>
					</li>
					<li role="presentation"><a href="#depot" aria-controls="depot" role="tab" data-toggle="tab">直接托管</a>
					</li>
				</ul>
			</div>
			<ul role="tabpanel" class="list-group tab-pane active" id="contain">
				<li class="list-group-item row" ng-repeat="item in rule.keyword" ng-if="item.type==2">
					<div class="col-xs-12 col-sm-8">
						<input type="text" class="form-control" ng-show="item.edited" ng-model="item.content"
						       onblur="util.checkWxKeyword(this,{{q('get.rid',0)}})">
						<span class="help-block has_keyword"></span>
						<p class="form-control-static" ng-hide="item.edited" ng-bind="item.content"></p>
					</div>
					<div class="col-sm-4">
						<div class="btn-group">
							<button type="button" class="btn btn-default" ng-click="saveItem(item)">
								@@{{item.edited?'编辑':'保存'}}
							</button>
							<button type="button" class="btn btn-default" ng-click="removeItem(item)">删除</button>
						</div>
					</div>
				</li>
			</ul>
			<ul role="tabpanel" class="list-group tab-pane " id="regexp">
				<li class="list-group-item row" ng-repeat="item in rule.keyword" ng-if="item.type==3">
					<div class="col-xs-12 col-sm-8">
						<input type="text" class="form-control" ng-show="item.edited" ng-model="item.content"
						       onblur="util.checkWxKeyword(this,{{q('get.rid',0)}})">
						<span class="help-block has_keyword"></span>
						<p class="form-control-static" ng-hide="item.edited" ng-bind="item.content"></p>
					</div>
					<div class="col-sm-4">
						<div class="btn-group">
							<button type="button" class="btn btn-default" ng-click="saveItem(item)">
								@@{{item.edited?'编辑':'保存'}}
							</button>
							<button type="button" class="btn btn-default" ng-click="removeItem(item)">删除</button>
						</div>
					</div>
				</li>
			</ul>
			<ul role="tabpanel" class="list-group tab-pane " id="depot">
				<li class="list-group-item row" ng-repeat="item in rule.keyword" ng-if="item.type==4">
					<div class="col-xs-12 col-sm-12">
						<span>符合优先级条件时, 这条回复将直接生效</span>
						<button class="btn btn-default" type="button" ng-click="removeItem(item)">取消托管</button>
					</div>
				</li>
			</ul>

			<div class="panel-footer advFooter">
				<button type="button" class="btn btn-default" ng-click="addItem()">添加包含关键字</button>
				<div ng-bind-html="footer.help"></div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="keyword"/>
<script>
	require(['angular', 'underscore', 'util', 'jquery', 'hdcms'], function (angular, _, util, $, hdcms) {
		angular.module('app', []).controller('ctrl', ['$scope', '$sce', function ($scope, $sce) {
			//当前操作的关键词类型
			$scope.currentKeyType = 'contain';
			$scope.footer = {
				contain: '<span class="help-block"> 用户进行交谈时，对话中包含上述关键字就执行这条规则。</span>',
				regexp: '<span class="help-block">用户进行交谈时，对话内容符合述关键字中定义的模式才会执行这条规则。<br><strong>注意：如果你不明白正则表达式的工作方式，请不要使用正则匹配</strong> <br><strong>注意：正则匹配使用MySQL的匹配引擎，请使用MySQL的正则语法</strong> <br><strong>示例: </strong><br><em>^微信</em>匹配以“微信”开头的语句<br><em>微信$</em>匹配以“微信”结尾的语句<br><em>^微信$</em>匹配等同“微信”的语句<br><em>微信</em>匹配包含“微信”的语句<br><em>[0-9.-]</em>匹配所有的数字，句号和减号<br><em>^[a-zA-Z_]$</em>所有的字母和下划线<br><em>^[[:alpha:]]{3}$</em>所有的3个字母的单词<br><em>^a{4}$</em>aaaa<br><em>^a{2,4}$</em>aa，aaa或aaaa<br><em>^a{2,}$</em>匹配多于两个a的字符串</span>',
				depot: '<span class="help-block">如果没有比这条回复优先级更高的回复被触发，那么直接使用这条回复。<br/><strong>注意：如果你不明白这个机制的工作方式，请不要使用直接接管。</strong></span>'
			};
			$scope.footer.help = $sce.trustAsHtml($scope.footer.contain);
			$scope.rule =<?php echo json_encode( $rule );?>;
			//高级触发
			$scope.advancedTriggering = false;
			//添加时没有关键词信息,所以初始数据用于页面展示
			if (!$scope.rule) {
				$scope.rule = {name: '', rank: 0, status: 1, keyword: [{content: '', type: 1}]};
			} else {
				for (var i = 0; i < $scope.rule.keyword.length; i++) {
					if ($scope.rule.keyword[i].type > 1) {
						$scope.advancedTriggering = true;
					}
				}
			}
			//如果没有触发关键字时添加触发关键字用于显示触发关键字input表单
			var hasDefaultKeyword = false;
			for (var i in $scope.rule.keyword) {
				if ($scope.rule.keyword[i].type == 1) {
					hasDefaultKeyword = true;
				}
				$scope.rule.keyword[i].edited = true;
			}
			if (hasDefaultKeyword === false) {
				$scope.rule.keyword.push({content: '', type: 1});
			}
			//置顶设置
			$scope.rule.istop = $scope.rule.rank == 255 ? 1 : 0;
			//选择表情用于触发关键字
			util.emotion({
				btn: '#keywordEmotion',
				input: '#keywordInput'
			});
			//修改关键词状态当edited为true时隐藏文本框
			$scope.saveItem = function (item) {
				item.edited = !item.edited;
			}
			//删除关键词
			$scope.removeItem = function (item) {
				$scope.rule.keyword = _.without($scope.rule.keyword, item);
			}
			//切换面板
			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				active = e.target.hash.replace(/#/, '');
				$scope.footer.help = $sce.trustAsHtml($scope.footer[active]);
				$scope.currentKeyType = active;
				$scope.$digest();
			});
			//添加关键词
			$scope.addItem = function () {
				//edited为显示文本输入框
				var item = '';
				switch ($scope.currentKeyType) {
					case 'contain':
						item = {content: '', type: 2, edited: true};
						break;
					case 'regexp':
						item = {content: '', type: 3, edited: true};
						break;
					case 'depot':
						//直接托管
						var status = false;
						for (var i in $scope.rule.keyword) {
							if ($scope.rule.keyword[i].type == 4) {
								status = true;
							}
						}
						if (status == false) {
							item = {content: '直接托管', type: 4, edited: true};
						}
						break;
				}
				if (item) {
					$scope.rule.keyword.push(item);
				}
			}
			//初始化模块控制器
			if (angular.isFunction(window.moduleController)) {
				window.moduleController($scope, _, util);
			}
			//检测输入的关键词是否已经被使用
			$scope.checkWxKeyword = function (elem, rid) {
				var obj = $(elem.currentTarget);
				hdcms.checkWxKeyword(obj.val(), rid, function (res) {
					if (res.valid == 0) {
						obj.next().html(res.message);
					}else{
						obj.next().html('');
					}
				})
			}

			//提交表单
			$("#replyForm").submit(function () {
				//验证关键词是否已经存在
				var has_keyword = false;
				$('.has_keyword').each(function () {
					if ($.trim($(this).text())) {
						util.message('触发关键字已经被其他规则使用了,请更换触发关键字', '', 'warning');
						has_keyword = true;
					}
				});
				if (has_keyword) {
					return false;
				}
				//验证回复规则名称
				if ($scope.rule.name == '') {
					util.message('请输入回复规则名称', '', 'warning');
					return false;
				}
				if (angular.isFunction(window.validateForm)) {
					if (!window.validateForm(this, $, $scope, util, _)) {
						return false;
					}
				}
				$("[name='keyword']").val(angular.toJson($scope.rule));
				return true;
			})
		}]);
		$(function () {
			angular.bootstrap($("#replyForm")[0], ['app']);
		});
	});

</script>
<style>
	/*表情选择框*/
	.popover {
		max-width: none;
	}

	.row {
		margin-right: 0px;
		margin-left: 0px;
	}
</style>