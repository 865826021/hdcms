<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">已经安装模块</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="?s=system/package/lists">服务套餐列表</a></li>
		<li role="presentation"><a href="?s=system/package/post">添加套餐</a></li>
	</ul>
	<form action="" method="post" id="form" onsubmit="return false;">
		<div class="panel">
			<div class="panel-body">
				<table class="table">
					<thead>
					<tr>
						<th width="50">删除</th>
						<th>名称</th>
						<th>可用模块</th>
						<th>可用模板</th>
						<th>操作</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td><input type="checkbox" disabled="disabled"></td>
						<td>基础服务 <span class="label label-info">系统</span></td>
						<td>系统模块</td>
						<td>系统模板</td>
						<td></td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="name" disabled="disabled">
						</td>
						<td>所有服务 <span class="label label-info">系统</span></td>
						<td>
							<span class="label label-success">系统所有模块</span>
						</td>
						<td><span class="label label-success">系统所有模板</span></td>
						<td>&nbsp;</td>
					</tr>
					<foreach from="$data" value="$d">
						<tr>
							<td><input type="checkbox" name="id[]" value="{{$d['id']}}"></td>
							<td>{{$d['name']}}</td>
							<td>
								<ul class="module-list">
									<li>系统模块</li>
									<if value="!empty($d['modules'])">
										<foreach from="$d['modules']" value="$m">
											<li>{{$m}}</li>
										</foreach>
									</if>
								</ul>
							</td>
							<td>
								<ul class="module-list">
									<li>系统模板</li>
									<if value="!empty($d['template'])">
										<foreach from="$d['template']" value="$t">
											<li>{{$t}}</li>
										</foreach>
									</if>
								</ul>
							</td>
							<td>
								<a href="?s=system/package/post&id={{$d['id']}}">编辑</a>
							</td>
						</tr>
					</foreach>
					</tbody>
				</table>
			</div>
		</div>
		<button type="button" class="btn btn-primary" onclick="del()">删除选中套餐</button>
	</form>
</block>
<script>
	function del() {
		require(['util'], function (util) {
			util.confirm('确定删除所选套餐吗?', function () {
				$.post('?s=system/package/remove', $('form').serialize(), function (res) {
					if (res.valid == 1) {
						util.message('删除成功', 'refresh');
					}
				}, 'json');
			})
		})
	}
</script>
<style>
	ul.module-list {
		padding : 0px;
	}
	ul.module-list li {
		float      : left;
		padding    : 8px;
		list-style : none;
	}
</style>
