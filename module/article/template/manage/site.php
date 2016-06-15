<extend file="resource/view/site"/>
<block name="content">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#tab1">微站管理</a></li>
        <li><a href="{{site_url('SitePost')}}">添加站点</a></li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">可用的微站</h3>
        </div>
        <div class="panel-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>微站名称</th>
                    <th>关键字</th>
                    <th>风格</th>
                    <th>模板</th>
                    <th width="260">操作</th>
                </tr>
                </thead>
                <tbody>
                <foreach from="$data" value="$d">
                    <tr>
                        <td>{{$d['title']}}</td>
                        <td>{{$d['site_info']['keyword']}}</td>
                        <td>{{$d['site_info']['template_title']}}</td>
                        <td>theme/{{$d['site_info']['template_name']}}</td>
                        <td>
                            <a href="{{site_url('sitePost')}}&webid={{$d['id']}}">编辑</a>
                            <a href="{{site_url('slide/lists')}}&webid={{$d['id']}}">幻灯片</a>
                            <a href="{{u('site/nav/lists')}}&entry=home&webid={{$d['id']}}">微站导航菜单</a>
                            <a href="javascript:;" url="{{$d['url']}}&t=web" class="copy">复制链接</a>
                            <a href="javascript:preview('{{$d['url']}}&t=web&mobile=1');">预览</a>
                        </td>
                    </tr>
                </foreach>
                </tbody>
            </table>
        </div>
    </div>
</block>
<style>
    /*预览模态框*/
    #premobile .modal-body {
        padding : 0px;
    }
</style>
<script>
    require(['util'], function (util) {
        $('.copy').each(function () {
            var This = this;
            util.zclip(This, $(This).attr('url'))
        });
    });
    //预览
    function preview(url) {
        require(['util'], function (util) {
            var obj = util.modal({
                title: '预览微网站',//标题
                id: 'premobile',
                content: '<iframe width="320" height="480" src="'+url+'" frameborder="0"></iframe>',//内容
                footer: '<button type="button" class="btn btn-default confirm" data-dismiss="modal">关闭</button>',//底部
                width: 322,//宽度
            });
            //显示模态框
            obj.modal('show');
        });
    }

</script>