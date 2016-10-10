<extend file="resource/view/system"/>
<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">更新缓存</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="{{u('lists')}}">更新缓存</a></li>
    </ul>
    <form action="{{__URL__}}" class="form-horizontal" method="post">
        <div class="form-group">
            <label class="col-sm-1 control-label">缓存类型</label>

            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox" name="data" value="1" checked="checked"> 数据缓存
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="tpl" value="1" checked="checked"> 模板编译
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="weixin" value="1" checked="checked"> 微信数据
                </label>
            </div>
        </div>

        <button class="btn btn-primary col-sm-offset-1">提交更新</button>
    </form>
</block>