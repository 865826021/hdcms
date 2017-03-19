<extend file="resource/view/site"/>
<link rel="stylesheet" href="{{__VIEW__}}/entry/css/package.css">
<block name="content">
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="#">扩展模块列表</a></li>
	</ul>
	<div class="row apps">
		<foreach from="Module::getBySiteUser()" value="$d">
			<div class="col-sm-6 col-md-2">
				<div class="thumbnail">
					<div class="img">
						<img src="addons/{{$d['name']}}/{{$d['preview']}}">
					</div>
					<div class="caption">
						<h4>
							<a href="{{site_url('site.entry.module',['m'=>$d['name'],'mt'=>'default'])}}">{{$d['title']}}</a>
						</h4>
					</div>
				</div>
			</div>
		</foreach>
	</div>
</block>