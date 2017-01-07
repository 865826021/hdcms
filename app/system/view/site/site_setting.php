<extend file="resource/view/system"/>
<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">设置网站基本信息</li>
    </ol>

    <form action="" method="post" role="form" class="form-horizontal">
        {{csrf_field()}}
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
                    <label class="col-sm-2 control-label star">网站描述</label>
                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" cols="30" rows="5" required="required"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">网站域名</label>
                    <div class="col-sm-10">
                        <input type="text" name="domain" class="form-control" placeholder="如: www.houdunwang.com">
                        <span class="help-block">站点使用的域名,不要添加http</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">默认模块</label>
                    <div class="col-sm-10">
                        <input type="text" name="module" class="form-control" value="{{$site['module']}}">
                        <span class="help-block">通过域名访问时运行的模块,需要设置 桌面入口导航</span>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
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