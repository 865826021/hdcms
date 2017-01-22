<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs">
		<li role="presentation" class="active">
			<a href="#">扩展功能概况 - {{v('module.title')}}</a>
		</li>
	</ul>
	<?php $LINKS = \Menu::get(); ?>
	<div class="page-header">
		<h4>系统功能</h4>
		<div class="menuLists clearfix">
			<if value="$LINKS['module']['budings']['cover']">
				<foreach from="$LINKS['module']['budings']['cover']" value="$f">
					<a href="?s=site/cover/post&mark=package&m={{$LINKS['module']['name']}}&bid={{$f['bid']}}">
						<i class="fa fa-file-image-o"></i>
						<span>{{$f['title']}}</span>
					</a>
				</foreach>
			</if>
			<if value="$LINKS['module']['rule']">
				<a href="?s=site/rule/lists&mark=package&m={{$LINKS['module']['name']}}">
					<i class="fa fa-rss"></i>
					<span>回复规则列表</span>
				</a>
			</if>
			<if value="$LINKS['module']['setting']">
				<a href="?s=site/config/post&mark=package&m={{$LINKS['module']['name']}}">
					<i class="fa fa-cog"></i>
					<span>参数设置</span>
				</a>
			</if>
			<if value="$LINKS['module']['middleware']">
				<a href="?s=site/middleware/lists&m={{$LINKS['module']['name']}}">
					<i class="fa fa-globe"></i>
					<span>中间件设置</span>
				</a>
			</if>
			<if value="$LINKS['module']['router']">
				<a href="?s=site/router/post&mark=package&m={{$LINKS['module']['name']}}">
					<i class="fa fa-tachometer"></i>
					<span>路由规则</span>
				</a>
			</if>
			<if value="!empty($LINKS['module']['budings']['home'])">
				<a href="?s=site/navigate/lists&entry=home&mark=package&m={{$LINKS['module']['name']}}">
					<i class="fa fa-home"></i>
					<span>微站首页导航</span>
				</a>
			</if>
			<if value="!empty($LINKS['module']['budings']['profile'])">
				<a href="?s=site/navigate/lists&entry=profile&mark=package&m={{$LINKS['module']['name']}}">
					<i class="fa fa-github"></i>
					<span>移动端会员中心导航</span>
				</a>
			</if>
			<if value="!empty($LINKS['module']['budings']['member'])">
				<a href="?s=site/navigate/lists&entry=member&mark=package&m={{$LINKS['module']['name']}}">
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