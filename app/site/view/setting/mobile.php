<extend file="resource/view/site"/>
<block name="content">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#">邮件通知</a></li>
    </ul>
    <form action="" method="post" class="form-horizontal" id="app" v-cloak>
        <div class="panel panel-default">
            <div class="panel-heading">
                短信通知设置
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">短信服务商</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" value="aliyun" v-model="field.provider">
                            阿里云
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">accessKey</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" v-model="field.aliyun.accessKey">
                        <span class="help-block">
                           登录阿里云后台管理平台查看
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">accessSecret</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" v-model="field.aliyun.accessSecret">
                        <span class="help-block">
                            登录阿里云后台管理平台查看
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">签名sign</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" v-model="field.aliyun.sign">
                        <span class="help-block">
                            登录阿里云短信模板管理界面查看
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <textarea name="data" hidden="hidden">@{{JSON.stringify(field)}}</textarea>
        <button type="submit" class="col-lg-1 btn btn-primary">保存</button>
    </form>
</block>

<script>
    require(['vue'], function (Vue) {
        new Vue({
            el: '#app',
            data: {
                field: JSON.parse('{{$sms}}')
            }
        })
    })
</script>