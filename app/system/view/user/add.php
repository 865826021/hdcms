<extend file="resource/view/system"/>
<block name="content">
	<if value="!IS_AJAX">
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i></li>
			<li><a href="?s=system/manage/menu">系统</a></li>
			<li class="active">添加用户</li>
		</ol>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="{{u('lists')}}">用户列表</a></li>
			<li role="presentation" class="active"><a href="{{u('add')}}">添加用户</a></li>
		</ul>

		<h5 class="page-header">添加新用户</h5>
	</if>
	<form action="" class="form-horizontal ajaxfrom" method="post">
		{{csrf_field()}}
		<div class="form-group">
			<label class="col-sm-2 control-label star">用户名</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" name="username" required="required">
				<span id="helpBlock" class="help-block">请输入用户名，用户名为 3 到 15 个字符组成，包括汉字，大小写字母（不区分大小写）</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label star">密码</label>

			<div class="col-sm-10">
				<input type="password" class="form-control" name="password" required="required">
				<span class="help-block">请填写密码，最小长度为 6 个字符</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label star">确认密码</label>

			<div class="col-sm-10">
				<input type="password" class="form-control" name="password2" required="required">
				<span class="help-block">重复输入密码，确认正确输入</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">所属用户组</label>

			<div class="col-sm-10">
				<select class="js-example-basic-single form-control" name="groupid">
					<foreach from="$groups" value="$g">
						<option value="{{$g['id']}}">{{$g['name']}}</option>
					</foreach>
				</select>
				<span class="help-block">分配用户所属用户组后，该用户会自动拥有此用户组内的模块操作权限</span>
                <span class="help-block">
                    <strong class="text-danger">设置用户组后，系统会根据对应用户组的服务期限对用户的服务开始时间和结束时间进行初始化</strong>
                </span>

			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">备注</label>

			<div class="col-sm-10">
				<textarea class="form-control" rows="3" name="remark"></textarea>
			</div>
		</div>
		<button class="btn btn-primary col-sm-offset-2">确定注册</button>
	</form>
</block>