<extend file="resource/view/system"/>
<block name="content">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i></li>
            <li><a href="?s=system/manage/menu">系统</a></li>
            <li class="active">我的帐户</li>
        </ol>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="{{u('lists')}}">帐户密码</a></li>
        </ul>
    <form action="{{__URL__}}" class="form-horizontal" method="post">
        <div class="form-group">
            <label class="col-sm-2 control-label">用户名</label>

            <div class="col-sm-10">
                <input type="text" disabled="disabled" value="{{v('user.info.username')}}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">密码</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="password">
                <span class="help-block">请填写密码，最小长度为 6 个字符</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">确认密码</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="password2">
                <span class="help-block">重复输入密码，确认正确输入</span>
            </div>
        </div>
        <button class="btn btn-primary col-sm-offset-2">保存修改</button>
    </form>
</block>