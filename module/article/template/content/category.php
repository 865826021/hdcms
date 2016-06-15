<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#tab1">栏目管理</a></li>
		<li><a href="?a=article/content/categoryPost&t=site">添加栏目</a></li>
	</ul>
	<form action="" method="post">
		<div class="panel panel-default">

			<div class="panel-body">
				<table class="table table-hover">
					<thead>
					<tr>
						<th width="80">编号</th>
						<th width="100">显示顺序</th>
						<th>分类名称</th>
						<th>微站首页导航</th>
						<th width="200">操作</th>
					</tr>
					</thead>
					<tbody>
					<foreach from="$data" value="$d">
						<tr>
							<td>{{$d['cid']}}</td>
							<td>
								<input type="text" class="form-control" name="orderby[{{$d['cid']}}]" value="{{$d['orderby']}}">
							</td>
							<td>{{$d['_title']}}</td>
							<td>{{$d['ishomepage']?'是':'否'}}</td>
							<td>
								<a href="javascript:;" class="copy" url='{{$d["url"]}}'>复制链接</a>
								<a href="?a=article/content/article&t=site&cid={{$d['cid']}}">文章管理</a>
								<a href="?a=article/content/categoryPost&t=site&cid={{$d['cid']}}">编辑</a>
								<a href="javascript:;" onclick="del({{$d['cid']}})">删除</a>
							</td>
						</tr>
					</foreach>
					</tbody>
				</table>
			</div>

		</div>
		<button class="btn btn-primary">提交</button>
	</form>
</block>
<script>
	require(['util'], function (util) {
		$('.copy').each(function () {
			var This = this;
			util.zclip(This, $(This).attr('url'))
		});
	});

	function del(cid) {
		util.confirm('确定删除吗?', function () {
			$.get('?a=article/content/removeCat&t=site&cid=' + cid, function (res) {
				if (res.valid) {
					location.reload(true);
				} else {
					util.message(res.message, '', 'error');
				}
			}, 'json');
		})
	}
</script>