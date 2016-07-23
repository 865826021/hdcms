<extend file="resource/view/system"/>
<link rel="stylesheet" href="{{__VIEW__}}/template/css.css">
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">已经安装模板</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation"><a href="{{u('installed')}}">已经安装模板</a></li>
		<li role="presentation" class="active"><a href="?s=system/template/prepared">安装模板</a></li>
		<li role="presentation"><a href="?s=system/template/design">设计新模板</a></li>
		<li role="presentation"><a href="http://www.hdcms.com/store">应用商城</a></li>
	</ul>
	<h5 class="page-header">已购买的模板</h5>
	<h5 class="page-header">未安装的本地模板</h5>
	<div class="template">
		<foreach from="$locality" value="$m">
			<div class="thumbnail action">
				<h5>{{$m['title']}}({{$m['name']}})</h5>
				<img class="media-object" src="{{$m['thumb']}}"/>
				<div class="caption">
					<a class="btn btn-default btn-xs btn-block" href="{{u('install',array('name'=>$m['name']))}}">安装模板</a>
				</div>
			</div>
		</foreach>
	</div>
</block>
