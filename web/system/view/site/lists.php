<extend file="resource/view/system"/>

<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="#">首页</a></li>
		<li>网站列表</li>
	</ol>
	<if value="v('user.groupid') gte 1">
		<div class="alert alert-warning">
			温馨提示：
			<i class="fa fa-info-circle"></i>
			Hi，<span class="text-strong">{{v('user.username')}}</span>，您所在的会员组 <span class="text-strong">{{v('user.name')}}</span>，
			账号有效期限：
			<span class="text-strong">{{date('Y-m-d',v('user.starttime'))}} ~~ {{v('user.endtime')?date('Y-m-d',v('user.endtime')):'无限制'}}</span>，
			可添加 <span class="text-strong">{{v('group.maxsite')}} </span>个站点，已添加<span class="text-strong">
                <?php echo $siteNum = Db::table( 'site_user' )->where( 'uid', v( 'user.uid' ) )->where( 'role', 'owner' )->count(); ?>
            </span>个，还可添加 <span class="text-strong">{{v('group.maxsite')-$siteNum}} </span>个公众号。
		</div>
	</if>
	<div class="clearfix">
		<div class="input-group">
			<a href="?s=system/site/post&step=site_setting" class="btn btn-primary"><i class="fa fa-plus"></i> 添加网站</a>
		</div>
	</div>
	<br/>

	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">筛选</h3>
		</div>
		<div class="panel-body">
			<form action="?s=system/site/lists" method="post" class="form-horizontal" role="form">
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">搜索</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="text" class="form-control" id="sitename" name="sitename" placeholder="请输入网站名称">
							<input type="text" class="form-control hide" id="domain" name="domain" placeholder="请输入网站域名">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
								<button type="button" class="btn btn-default dropdown-toggle"
								        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" onclick="$('#domain').addClass('hide').val('');$('#sitename').removeClass('hide')">根据网站名称搜索</a>
									</li>
									<li><a href="#" onclick="$('#sitename').addClass('hide').val('');$('#domain').removeClass('hide')">根据公众号中称搜索</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</form>
			<foreach from="$sites" value="$s">
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<div class="col-xs-6" style="position: relative">
                            <span class="package" siteid="{{$s['siteid']}}">
                                套餐:
	                            <?php if ( empty( $s['owner'] ) ) { ?>
		                            所有套餐
	                            <?php } else { ?>
		                            {{$s['owner']['group_name']}}
	                            <?php }; ?>
                            </span>
						</div>
						<div class="col-xs-6 text-right">
							<a href="?s=site/entry/refer&siteid={{$s['siteid']}}" class="text-info">
								<strong><i class="fa fa-cog"></i> 管理站点</strong>
							</a>
						</div>
					</div>

					<div class="panel-body clearfix" style="padding:8px 15px;">
						<div class="col-xs-4 col-md-1">
							<if value="$s['icon']">
								<img src="{{$s['icon']}}" style="width:50px;height:50px;border-radius: 5px;border:solid 1px #dcdcdc;">
								<else/>
								<i class="fa fa-rss fa-4x"></i>
							</if>
						</div>
						<div class="col-xs-4 col-md-5">
							<h4 style="line-height:35px;overflow: hidden;height:30px;">{{$s['name']}}</h4>
						</div>
						<div class="col-xs-4 col-md-6 text-right" style="line-height:60px;height:45px;">
							<if value="$s['is_connect']">
								<a href="javascript:;" data-toggle="tooltip" data-placement="top" title="接入状态: 接入成功">
									<i class="fa fa-check-circle fa-2x text-success"></i>
								</a>
								<else/>
								<a href="javascript:;" data-toggle="tooltip" data-placement="top" title="公众号接入失败,请重新修改公众号配置文件并进行连接测试.">
									<i class="fa fa-times-circle fa-2x text-warning"></i>
								</a>
							</if>
						</div>
					</div>

					<div class="panel-footer clearfix">
						<div class="col-xs-6">
							服务有效期 :
							<?php if ( empty( $s['owner'] ) ) { ?>
								无限制
							<?php } else if ( $s['endtime'] == 0 ) { ?>
								无限制
							<?php } else if ( $s['endtime'] < time() ) { ?>
								<span class="label label-danger">已到期</span>
							<?php } else { ?>
								{{date("Y-m-d", $s['starttime'])}}~{{$s['endtime']==0?'无限制':date("Y-m-d", $s['endtime'])}}
							<?php } ?>
						</div>
						<div class="col-xs-6 text-right">
							<?php if ( m( "User" )->isSuperUser() ) { ?>
								<a href="?s=system/site/post&step=access_setting&siteid={{$s['siteid']}}&from=lists">
									<i class="fa fa-key"></i> 设置权限
								</a>&nbsp;&nbsp;&nbsp;
							<?php } ?>
							<?php if ( m( "UserPermission" )->isOwner( $s['siteid'] ) ) { ?>
								<a href="?s=system/permission/users&siteid={{$s['siteid']}}"><i class="fa fa-user"></i> 操作员管理</a>&nbsp;&nbsp;&nbsp;
							<?php } ?>
							<?php if ( m( "UserPermission" )->isManage( $s['siteid'] ) ) { ?>
								<a href="javascript:;" onclick="delSite({{$s['siteid']}})"><i class="fa fa-trash"></i> 删除</a>&nbsp;&nbsp;&nbsp;
								<a href="?s=system/site/edit&siteid={{$s['siteid']}}"><i class="fa fa-pencil-square-o"></i> 编辑</a>
							<?php } ?>
						</div>
					</div>
				</div>
			</foreach>
		</div>
	</div>
	<script>
		//获取站点套餐信息
		$(".package").mouseover(function () {
			$(".packageInfo").remove();
			var This = this;
			var siteid = $(this).attr('siteid');
			var groupid = $(this).attr('groupid');
			//获取权限列表
			$.get('?s=system/site/package', {siteid: siteid}, function (data) {
				if (data.package.length > 0 || data.modules.length > 0) {
					var html = '<div class="popover right packageInfo"><div class="arrow"></div><div class="popover-content">';
				}
				if (data.package.length) {
					html += '<p style="margin-bottom: 8px;">可用的服务套餐</p>';
					$(data.package).each(function (i) {
						html += '<p style="margin-bottom: 5px;"><span class="label label-success">' + data.package[i] + '</span></p>';
					});
				}
				if (data.modules.length > 0) {
					html += '<p style="margin: 10px 0px 5px;">附加的模块权限</p>';
					$(data.modules).each(function (i) {
						if (data.modules[i].is_system == 0) {
							html += '<span class="label label-success">' + data.modules[i].title + '</span>&nbsp;';
							if (i % 3) {
								html += '<div style="margin-bottom: 6px;"></div>'
							}
						}
					})
				}
				if (html) {
					$(This).after(html += '</div></div>');
					var l = This.offsetLeft + $(This).width() + 5;
					var t = This.offsetTop - $(".packageInfo").height() / 2 + 5;
					$(".packageInfo").css({left: l, top: t}).show();
				}
			}, 'json');
		}).mouseout(function () {
			$(".packageInfo").remove();
		});

		require(['bootstrap'], function ($) {
			$(function () {
				$('[data-toggle="tooltip"]').tooltip()
			})
		})

		//删除站点
		function delSite(siteid) {
			require(['util'], function (util) {
				util.confirm('删除站点将删除所有站点数据, 确定删除吗?', function () {
					$.get('?s=system/site/remove&siteid=' + siteid, function (res) {
						if (res.valid) {
							util.message(res.message,'refresh','success');
						} else {
							util.message(res.message, '', 'error');
						}
					}, 'json');
				})
			})
		}
	</script>
	<style>
		.package {
			cursor : pointer;
		}
	</style>
</block>
