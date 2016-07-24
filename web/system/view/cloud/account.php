<extend file="resource/view/system"/>
<block name="content">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i></li>
            <li><a href="?s=system/manage/menu">系统</a></li>
            <li class="active">云帐号</li>
        </ol>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#">云帐号</a></li>
        </ul>
    <form action="" class="form-horizontal" method="post">
        <div class="form-group">
            <label class="col-sm-2 control-label">AppID(应用ID)</label>
            <div class="col-sm-10">
                <input type="text" name="AppID" value="{{$field['AppID']}}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">AppSecret(应用密钥)</label>
            <div class="col-sm-10">
                <input type="text" name="AppSecret" value="{{$field['AppSecret']}}" class="form-control">
            </div>
        </div>
        <button class="btn btn-primary col-sm-offset-2">保存并测试连接</button>
    </form>
</block>