<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li>
			<a href="?s=system/permission/users&siteid={{$_GET['siteid']}}">帐号操作人员列表</a>
		</li>
		<li class="active">操作人员权限设置</li>
	</ol>
	<div class="alert alert-info" role="alert">
		<span class="fa fa-info-circle"></span>
		默认未勾选任何菜单时，用户拥有全部权限。
	</div>
	<form action="" method="post">
		<input type="hidden" name="uid" value="{{$_GET['fromuid']}}">
		<input type="hidden" name="siteid" value="{{$_GET['siteid']}}">
		<div class="panel panel-default">
			<div class="panel-body" style="overflow:visible">
				<table class="table">
					<foreach from="$menus" value="$f">
						<thead>
						<tr class="info">
							<th colspan="6">
								<div class="checkbox">
									<label>
										<input type="checkbox"> {{$f['title']}}
									</label>
								</div>
							</th>
						</tr>
						</thead>
						<tbody>
						<?php $id = 0;?>
						<foreach from="$f['_data']" value="$d">
							<foreach from="$d['_data']" key="$k" value="$m">
								<if value="$id==0">
									<tr>
								</if>
								<?php $id ++; ?>
								<td>
									<div class="checkbox">
										<label>
											<if value="isset($permission['system']) && in_array($m['permission'],$permission['system'])">
												<input type="checkbox" name="system[]" value="{{$m['permission']}}"
												       checked="checked">{{$m['title']}}
												<else/>
												<input type="checkbox" name="system[]" value="{{$m['permission']}}">{{$m['title']}}
											</if>
										</label>
									</div>
								</td>
								<if value="$id==6">
									<?php $id = 0; ?>
									</tr>
								</if>
							</foreach>
						</foreach>
						</tbody>
					</foreach>
					<thead>
					<tr class="info">
						<th colspan="6">
							<div class="checkbox">
								<label>
									<input type="checkbox"> 模块权限
								</label>
							</div>
						</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<foreach from="$modules" value="$m">
							<if value="$m['is_system']==0">
								<td class="module-access">
									<div class="dropdown">
										<div class="checkbox dropdown-toggle hover" style="width: 150px">
											<label>
												<input type="checkbox"/> {{$m['title']}}
											</label>
										</div>
										<ul class="dropdown-menu">
											<!--参数设置-->
											<if value="$m['setting']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('setting',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="setting"
															       checked="checked"> 参数设置
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="setting">
															参数设置
														</if>
													</label>
												</li>
											</if>
											<!--参数设置-->
											<if value="$m['crontab']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('crontab',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="crontab"
															       checked="checked"> 定时任务
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="crontab">
															定时任务
														</if>
													</label>
												</li>
											</if>
											<if value="$m['router']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('router',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="router"
															       checked="checked"> 路由规则
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="router">
															路由规则
														</if>
													</label>
												</li>
											</if>
											<if value="$m['domain']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('domain',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="domain"
															       checked="checked"> 域名设置
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="domain">
															域名设置
														</if>
													</label>
												</li>
											</if>
											<if value="$m['middleware']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('middleware',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="middleware"
															       checked="checked"> 中间件设置
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="middleware">
															中间件设置
														</if>
													</label>
												</li>
											</if>
											<if value="$m['rule']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('rule',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="rule"
															       checked="checked"> 回复规则列表
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="rule">
															回复规则列表
														</if>
													</label>
												</li>
											</if>
											<if value="$m['cover']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('cover',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="cover"
															       checked="checked"> 封面回复
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="cover">
															封面回复
														</if>
													</label>
												</li>
											</if>
											<if value="$m['web']['member']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('web_member',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="web_member"
															       checked="checked"> 桌面个人中心导航
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="web_member">
															桌面个人中心导航
														</if>
													</label>
												</li>
											</if>
											<if value="$m['mobile']['member']">
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array('mobile_member',$permission[$m['name']])">
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="mobile_member"
															       checked="checked"> 移动端个人中心导航
															<else/>
															<input type="checkbox"
															       name="modules[{{$m['name']}}][]"
															       value="mobile_member">
															移动端个人中心导航
														</if>
													</label>
												</li>
											</if>

											<!--业务菜单因为有控制器方法所以单独处理-->
											<foreach from="$m['budings']['business']" value="$c">
												<li>
															<span class="text-info">
																<i class="fa fa-align-right"></i>{{$c['title']}}
															</span>
												</li>
												<!--读取业务动作菜单-->
												<foreach from="$c['do']" value="$f">
													<li>
														<label>
															<?php $access = strtolower('business_' . $c['controller'] . '_' . $f['do'] ); ?>
															<if value="isset($permission[$m['name']]) && in_array($access,$permission[$m['name']])">
																<input type="checkbox"
																       name="modules[{{$m['name']}}][]"
																       value="{{$access}}"
																       checked="checked"> {{$f['title']}}
																<else/>
																<input type="checkbox"
																       name="modules[{{$m['name']}}][]"
																       value="{{$access}}">
																{{$f['title']}}
															</if>
														</label>
													</li>
												</foreach>
											</foreach>

											<!--自定义权限-->
											<if value="$m['permissions']">
												<li>
													<span class="text-info">
														<i class="fa fa-beer"></i> 自定义标识
													</span>
												</li>
												<foreach from="$m['permissions']" value="$c">
													<?php $access = strtolower( 'custom_' . $c['do'] ); ?>
													<li>
														<label>
															<if value="isset($permission[$m['name']]) && in_array($access,$permission[$m['name']])">
																<input type="checkbox" name="modules[{{$m['name']}}][]"
																       value="{{$access}}"
																       checked="checked"> {{$c['title']}}
																<else/>
																<input type="checkbox" name="modules[{{$m['name']}}][]"
																       value="{{$access}}"> {{$c['title']}}
															</if>
														</label>
													</li>
												</foreach>
											</if>
										</ul>
									</div>
								</td>
							</if>
						</foreach>
						<style>
							.module-access .dropdown {
								cursor: pointer;
							}

							.module-access .dropdown-menu li {
								padding: 3px 15px;
								cursor: pointer
							}

							.module-access .dropdown-menu label {
								font-weight: normal;
							}

							.module-access .dropdown .dropdown-toggle {
								border-radius: 5px 5px 0px 0px;
								border: solid 1px #fff;
								padding: 8px 0 8px 15px !important;
							}

							.module-access .dropdown:hover .dropdown-toggle {
								background: #fff;
								border: solid 1px #ddd;
								border-bottom: 0px;
								z-index: 100 !important;
							}

							.module-access .dropdown ul {
								margin-top: -1px;
								z-index: 20;
								border-radius: 0 5px 5px 5px;
								padding: 5px 10px;
							}
						</style>
						<script>
							$(".dropdown").hover(function () {
								$(this).find('.dropdown-menu').show();
							}).mouseleave(function () {
								$(this).find('.dropdown-menu').hide();
							})
						</script>

					</tbody>
				</table>
			</div>
		</div>
		<button class="btn btn-primary">保存</button>
	</form>
</block>

<style>
	.table > thead > tr > th {
		border-bottom: 0;
		padding: 0px 8px;
	}

	.table > tbody > tr > td {
		border-top: 0;
	}

	.table .checkbox {
		padding: 0px;
	}

	.table > tbody > tr > td {
		padding: 0px 8px;
	}
</style>
<script>
	//选中或返选
	$("thead input").click(function () {
		//选中子表单
		var status = $(this).is(":checked");
		$(this).parents('thead').eq(0).next('tbody').find('input').prop('checked', status);
		JudgeParentInput();
	})
	$("tbody input").click(function () {
		JudgeParentInput();
	})
	//全选检测
	function JudgeParentInput() {
		$("tbody").each(function () {
			var inputLen = $(this).find('input').length;
			if (inputLen > 0 && inputLen == $(this).find('input:checked').length) {
				$(this).prev('thead').find('input').prop('checked', true);
			} else {
				$(this).prev('thead').find('input').prop('checked', false);
			}
		})
	}
	JudgeParentInput();

	//模块表单选择
	$(".dropdown-toggle input").click(function () {
		$(this).parents('.dropdown').find('input').prop('checked', $(this).is(":checked"));
	});
	util.submit();
</script>