<extend file="resource/view/system"/>
<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">已经安装模块</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="{{u('installed')}}">已经安装模块</a></li>
        <li role="presentation" class="active"><a href="?s=system/module/prepared">安装模块</a></li>
        <li role="presentation"><a href="?s=system/module/design">设计新模块</a></li>
        <li role="presentation"><a href="{{v('api.cloud')}}?a=site/store&t=web&siteid=1&m=store" target="_blank">应用商城</a></li>
    </ul>
    <div class="clearfix">
        <h5 class="page-header">安装 {{$module['application']['name']['@cdata']}}</h5>

        <div class="alert alert-info" role="alert">您正在安装 [{{$module['application']['title']['@cdata']}}] 模块. 请选择哪些公众号服务套餐组可使用 [{{$module['application']['title']['@cdata']}}] 模块功能 .</div>

        <h5 class="page-header">可用的公众号服务套餐组
            <small>这里来定义哪些公众号服务套餐组可使用 测试模块 功能</small>
        </h5>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">公众号服务套餐组</label>

            <form action="" method="post">
                <input type="hidden" name="module" value="{{$module['application']['name']['@cdata']}}">

                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" disabled>
                            基础服务 <span class="label label-success">系统</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" disabled="disabled" checked="checked">
                            所有服务 <span class="label label-success">系统</span>
                        </label>
                    </div>
                    <foreach from="$package" value="$p">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="package[]" value="{{$p['name']}}">
                                {{$p['name']}}
                            </label>
                        </div>
                    </foreach>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">确定继续安装 [{{$module['application']['title']['@cdata']}}] 模块</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</block>
