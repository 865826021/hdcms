<extend file="resource/view/site"/>
<block name="content">
	<!-- TAB NAVIGATION -->
	<ul class="nav nav-tabs" role="tablist">
		<li><a href="{{site_url('manage/site')}}">返回站点列表</a></li>
		<li class="active"><a href="javascript:;">管理</a></li>
		<li><a href="{{site_url('slide/post',array('webid'=>$_GET['webid']))}}">添加</a></li>
	</ul>
	<form action="" method="post">
		<div class="panel panel-default">
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
					<tr>
						<th width="150">排序</th>
						<th>缩略图</th>
						<th>标题</th>
						<th width="100">操作</th>
					</tr>
					</thead>
					<tbody>
					<foreach from="$data" value="$d">
						<tr>
							<td>
								<input type="text" class="form-control" name="slide[{{$d['id']}}]" value="{{$d['displayorder']}}">
							</td>
							<td>
								<img src="{{$d['thumb']}}" style="width:150px;">
							</td>
							<td>
								{{$d['title']}}
							</td>
							<td>
								<a href="{{site_url('slide/post')}}&webid={{$_GET['webid']}}&id={{$d['id']}}">编辑</a> -
								<a href="javascript:del({{$d['id']}})">删除</a>
							</td>
						</tr>
					</foreach>
					</tbody>
				</table>
			</div>
		</div>
		<button type="submit" class="btn btn-primary">确定</button>
	</form>
</block>

<script>
	function del(id) {
		util.confirm('确定幻灯图片吗,删除后将不可以恢复?', function () {
			location.href = '{{site_url('
			slide / remove
			')}}&webid={{$_GET['
			webid
			']}}&id=' + id;
		})
	}

	require(['util'], function (util) {
		$('.copy').each(function () {
			var This = this;
			util.zclip(This, $(This).attr('url'))
		});
	});
</script>