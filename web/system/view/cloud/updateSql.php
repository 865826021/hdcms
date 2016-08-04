<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">一键更新</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="#">系统更新</a></li>
	</ul>
	<form action="" class="form-horizontal">
		<div class="panel panel-default">
			<div class="panel-heading">
				正在更新数据表...
			</div>
			<div class="panel-body">
				<p style="margin: 0px;">
				<div class="progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
					     style="width: 100%">
						<span class="sr-only">45% Complete</span>
					</div>
				</div>
				</p>
			</div>
		</div>
	</form>
</block>
<script>
	//执行更新
	$.post("{{__URL__}}", function (res) {
		if (res.valid == 1) {
			alert('全部更新完成');
		} else {
			util.message(res.message);
		}
	}, 'json');
</script>