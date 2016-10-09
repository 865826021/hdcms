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
						<?php $id = 0; ?>
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
												<input type="checkbox" name="system[]" value="{{$m['permission']}}" checked="checked">{{$m['title']}}
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
											<foreach from="$m['budings']" value="$d">
												<foreach from="$d" value="$c">
													<li>
														<label>
															<if value="isset($permission[$c['module']]) && in_array($c['module'].'_'.$c['do'],$permission[$c['module']])">
																<input type="checkbox" name="modules[{{$c['module']}}][]"
																       value="{{$c['module']}}_{{$c['do']}}" checked="checked"> {{$c['title']}}
																<else/>
																<input type="checkbox" name="modules[{{$c['module']}}][]"
																       value="{{$c['module']}}_{{$c['do']}}"> {{$c['title']}}
															</if>
														</label>
													</li>
												</foreach>
											</foreach>
											<!--自定义权限-->
											<foreach from="$m['permissions']" value="$c">
												<?php $d = explode( ':', $c ); ?>
												<li>
													<label>
														<if value="isset($permission[$m['name']]) && in_array($d[1],$permission[$m['name']])">
															<input type="checkbox" name="modules[{{$m['name']}}][]" value="{{$d[1]}}"
															       checked="checked"> {{$d[0]}}
															<else/>
															<input type="checkbox" name="modules[{{$m['name']}}][]" value="{{$d[1]}}"> {{$d[0]}}
														</if>
													</label>
												</li>
											</foreach>
										</ul>
									</div>
								</td>
							</if>
						</foreach>
						<style>
							.module-access .dropdown {
								cursor : pointer;
							}

							.module-access .dropdown-menu li { padding : 3px 15px; cursor : pointer }

							.module-access .dropdown-menu label {
								font-weight : normal;
							}

							.module-access .dropdown .dropdown-toggle {
								border-radius : 5px 5px 0px 0px;
								border        : solid 1px #fff;
								padding       : 8px 0 8px 15px !important;
							}

							.module-access .dropdown:hover .dropdown-toggle {
								background    : #fff;
								border        : solid 1px #ddd;
								border-bottom : 0px;
								z-index       : 100 !important;
							}

							.module-access .dropdown ul {
								margin-top    : -1px;
								z-index       : 20;
								border-radius : 0 5px 5px 5px;
								padding       : 5px 10px;
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
		border-bottom : 0;
		padding       : 0px 8px;
	}

	.table > tbody > tr > td {
		border-top : 0;
	}

	.table .checkbox {
		padding : 0px;
	}

	.table > tbody > tr > td {
		padding : 0px 8px;
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
</script>