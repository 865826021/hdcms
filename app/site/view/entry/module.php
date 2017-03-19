<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs">
		<li role="presentation" class="active">
			<a href="#">扩展功能概况 - {{v('module.title')}}</a>
		</li>
	</ul>
	<?php $CurrentModule = Module::currentUseModule(); ?>
	<div class="page-header">
		<h4>系统功能</h4>
		<div class="menuLists clearfix">
			<if value="$CurrentModule['budings']['cover']">
				<foreach from="$LINKS['budings']['cover']" value="$f">
					<a href="{{site_url('site.cover.post')}}&bid={{$f['bid']}}">
						<i class="fa fa-file-image-o"></i>
						<span>{{$f['title']}}</span>
					</a>
				</foreach>
			</if>
			<if value="$CurrentModule['rule']">
				<a href="{{site_url('site.rule.lists')}}">
					<i class="fa fa-rss"></i>
					<span>回复规则列表</span>
				</a>
			</if>
			<if value="$CurrentModule['setting']">
				<a href="{{site_url('site.config.post')}}">
					<i class="fa fa-cog"></i>
					<span>参数设置</span>
				</a>
			</if>
			<if value="$CurrentModule['middleware']">
				<a href="{{site_url('site.middleware.post')}}">
					<i class="fa fa-globe"></i>
					<span>中间件设置</span>
				</a>
			</if>
			<if value="$CurrentModule['router']">
				<a href="{{site_url('site.router.lists')}}">
					<i class="fa fa-tachometer"></i>
					<span>路由规则</span>
				</a>
			</if>
			<if value="!empty($CurrentModule['budings']['home'])">
				<a href="{{site_url('site.navigate.lists')}}&entry=home">
					<i class="fa fa-home"></i>
					<span>微站首页导航</span>
				</a>
			</if>
			<if value="!empty($CurrentModule['budings']['profile'])">
				<a href="{{site_url('site.navigate.lists')}}&entry=profile">
					<i class="fa fa-github"></i>
					<span>移动端会员中心导航</span>
				</a>
			</if>
			<if value="!empty($CurrentModule['budings']['member'])">
				<a href="{{site_url('site.navigate.lists')}}&entry=member">
					<i class="fa fa-renren"></i>
					<span>桌面个人中心导航</span>
				</a>
			</if>
		</div>
	</div>
</block>
<style>
	.menuLists a {
		display      : block;
		float        : left;
		text-align   : center;
		margin-right : 1.2em;
		padding      : 8px 5px;
		min-width    : 7em;
		height       : 7em;
		overflow     : hidden;
		color        : #333;
	}

	.menuLists a i {
		display   : block;
		font-size : 3em;
		margin    : .28em .2em;
	}
</style>