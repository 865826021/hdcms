<extend file="resource/view/system"/>

<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">编辑站点资料</li>
    </ol>
    <form action="" method="post" role="form" class="form-horizontal">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">网站基本信息</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label star">网站名称</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="{{$site['name']}}">
                        <span class="help-block">网站中显示的网站名称,可以使用中文等任意字符</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label star">网站描述</label>

                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" cols="30" rows="5">{{$site['description']}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">网站域名</label>
                    <div class="col-sm-10">
                        <input type="text" name="domain" class="form-control" value="{{$site['domain']}}" placeholder="如: www.houdunwang.com">
                        <span class="help-block">站点使用的域名,不要添加http或https</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">默认模块</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control" name="module" value="{{$site['module']}}">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="getModuleHasWebPage()">选择模块</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
    </form>

</block>

<script>
    //上传图片
    function upImage(obj) {
        require(['util'], function (util) {
            util.image(function (images) {
                //上传成功的图片，数组类型
                if (images.length > 0) {
                    $(obj).parent().prev().val(images[0]);
                    $(obj).parent().parent().next().find('img').eq(0).attr('src',images[0]);
                }
            })
        })
    }

    //更新token
    function updateToken(obj) {
        require(['md5'], function (md5) {
            var token = md5(Math.random()).substr(0, 30);
            $("[name='token']").val(token);
        })
    }
    function updateEncodingaeskey() {
        require(['md5'], function (md5) {
            var encodingaeskey = md5(Math.random()).substr(0, 11)+md5(Math.random());
            $("[name='encodingaeskey']").val(encodingaeskey);
        })
    }
    //获取默认模块
    function getModuleHasWebPage(){
        require(['hdcms','util'],function(hdcms,util){
            hdcms.getModuleHasWebPage(function(module){
                $("[name='module']").val(module.name);
            });
        })
    }
</script>
<style>
    .nav li.normal {
        background: #eee;
    }

    .nav li.normal a, .nav li.normal a:active, .nav li.normal a:focus {
        border: none;
    }
</style>
