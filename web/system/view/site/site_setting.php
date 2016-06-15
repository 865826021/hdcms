<extend file="resource/view/system"/>
<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">设置网站基本信息</li>
    </ol>
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation" class="active"><a href="javascript:;">设置网站信息</a></li>
        <li role="presentation"><a href="javascript:;">设置公众号信息</a></li>
        <li role="presentation"><a href="javascript:;">设置权限</a></li>
        <li role="presentation"><a href="javascript:;">微信平台设置信息</a></li>
    </ul>
    <form action="?s=system/site/post&step=site_setting" method="post" role="form" class="form-horizontal">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">设置网站基本信息</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label star">网站名称</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" placeholder="如: 后盾网" required="required">
                        <span class="help-block">网站中显示的网站名称,可以使用中文等任意字符</span>
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-2 control-label">网站描述</label>

                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">下一步</button>
    </form>
</block>
<style>
    .nav li.normal {
        background: #eee;
    }

    .nav li.normal a, .nav li.normal a:active, .nav li.normal a:focus {
        border: none;
    }
</style>

<