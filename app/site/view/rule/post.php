<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs">
		<if value="!empty($_GET['rid'])">
			<li role="presentation">
				<a href="?s=site/rule/lists&m={{v('module.name')}}">管理{{v('module.title')}}</a>
			</li>
			<li role="presentation">
				<a href="?s=site/rule/post&m={{v('module.name')}}">
					<i class="fa fa-plus"></i> 添加{{v('module.title')}}
				</a>
			</li>
			<li role="presentation" class="active">
				<a href="#"><i class="fa fa-plus"></i> 编辑{{v('module.title')}}</a>
			</li>
			<else/>
			<li role="presentation">
				<a href="?s=site/rule/lists&m={{v('module.name')}}">管理{{v('module.title')}}</a>
			</li>
			<li role="presentation" class="active">
				<a href="#"><i class="fa fa-plus"></i> 添加{{v('module.title')}}</a>
			</li>
		</if>
	</ul>
	<form action="" method="post" class="form-horizontal ng-cloak" role="form" id="replyForm" ng-controller="ctrl">
		{{csrf_field()}}
		<div class="panel panel-default">
			<div class="panel-heading">
				添加回复规则
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">回复规则名称</label>
					<div class="col-sm-7 col-md-8">
						<input type="text" class="form-control" name="name" ng-model="rule.name">
						<span class="help-block">
							选择高级设置: 将会提供一系列的高级选项供专业用户使用.
						</span>
					</div>
					<div class="col-sm-3 col-md-2">
						<div class="checkbox">
							<label>
								<input type="checkbox" ng-model="advSetting" value="1">高级设置
							</label>
						</div>
					</div>
				</div>
				<include file="app/site/view/rule/keyword.php"/>
			</div>
		</div>
		{{$moduleForm}}
		<button class="btn btn-primary" type="submit">保存</button>
	</form>
</block>
