<extend file="resource/view/site"/>
<block name="content">
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="#">扩展功能概况 - {{v('module.title')}}</a></li>
	</ul>
	<div class="page-header">
		<h4>核心功能</h4>
		<div class="menuLists clearfix">
			<if value="$_LINKS_['module']['budings']['cover']">
				<foreach from="$_LINKS_['module']['budings']['cover']" value="$f">
					<a href="?s=site/module/cover&m={{$_LINKS_['module']['name']}}&bid={{$f['bid']}}">
						<i class="fa fa-comments"></i>
						<span>{{$f['title']}}</span>
					</a>
				</foreach>
			</if>
			<if value="$_LINKS_['module']['rule']">
				<a href="?s=site/reply/lists&m={{$_LINKS_['module']['name']}}">
					<i class="fa fa-comments"></i>
					<span>回复规则列表</span>
				</a>
			</if>
			<if value="$_LINKS_['module']['setting']">
				<a href="?s=site/module/setting&m={{$_LINKS_['module']['name']}}">
					<i class="fa fa-cog"></i>
					<span>参数设置</span>
				</a>
			</if>
			<if value="!empty($_LINKS_['module']['budings']['home'])">
				<a href="?s=site/nav/lists&entry=home&m={{$_LINKS_['module']['name']}}">
					<i class="fa fa-home"></i>
					<span>微站首页导航</span>
				</a>
			</if>
			<if value="!empty($_site_modules_menu_['budings']['profile'])">
				<a href="?s=site/nav/lists&entry=profile&m={{$_LINKS_['module']['name']}}">
					<i class="fa fa-github"></i>
					<span>手机个人中心导航</span>
				</a>
			</if>
			<if value="!empty($_LINKS_['module']['budings']['member'])">
				<a href="?s=site/nav/lists&entry=member&m={{$_LINKS_['module']['name']}}">
					<i class="fa fa-renren"></i>
					<span>桌面个人中心导航</span>
				</a>
			</if>
		</div>
	</div>
	<if value="$_LINKS_['module']['budings']['business']">
		<div class="page-header">
			<h4>业务菜单</h4>
			<div class="menuLists clearfix">

				<foreach from="$_LINKS_['module']['budings']['business']" value="$f">
					<a href="?s=site/module/business&m={{$_LINKS_['module']['name']}}&bid={{$f['bid']}}">
						<i class="fa fa-check-square-o"></i>
						<span>{{$f['title']}}</span>
					</a>
				</foreach>

			</div>
		</div>
	</if>
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