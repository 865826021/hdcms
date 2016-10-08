<extend file="resource/view/system"/>

<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">编辑站点资料</li>
    </ol>
    <form action="" method="post" role="form" class="form-horizontal">
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
                    <label class="col-sm-2 control-label star">域名</label>
                    <div class="col-sm-10">
                        <input type="text" name="domain" class="form-control" value="{{$site['domain']}}" placeholder="如: www.houdunwang.com">
                        <span class="help-block">站点使用的域名,不要添加http</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">网站基本信息</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label star">公众号名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="wename" class="form-control" value="{{$wechat['wename']}}">
                        <span class="help-block">填写公众号的帐号名称</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">微信号</label>

                    <div class="col-sm-10">
                        <input type="text" name="account" class="form-control" value="{{$wechat['account']}}">
                        <span class="help-block">填写公众号的帐号，一般为英文帐号</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">原始ID</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="original" value="{{$wechat['original']}}">
                        <span class="help-block">在给粉丝发送客服消息时,原始ID不能为空。建议您完善该选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">级别</label>

                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <if value="$wechat['level']==1">
                                <input type="radio" name="level" value="1" checked="checked"> 普通订阅号
                                <else/>
                                <input type="radio" name="level" value="1"> 普通订阅号
                            </if>
                        </label>
                        <label class="radio-inline">
                            <if value="$wechat['level']==2">
                                <input type="radio" name="level" value="2" checked="checked"> 普通服务号
                                <else/>
                                <input type="radio" name="level" value="2"> 普通服务号
                            </if>
                        </label>
                        <label class="radio-inline">
                            <if value="$wechat['level']==3">
                                <input type="radio" name="level" value="3" checked="checked"> 认证订阅号
                                <else/>
                                <input type="radio" name="level" value="3"> 认证订阅号
                            </if>
                        </label>
                        <label class="radio-inline">
                            <if value="$wechat['level']==4">
                                <input type="radio" name="level" value="4" checked="checked"> 认证服务号/认证媒体/政府订阅号
                                <else/>
                                <input type="radio" name="level" value="4"> 认证服务号/认证媒体/政府订阅号
                            </if>
                        </label>
                        <span class="help-block">注意：即使公众平台显示为“未认证”, 但只要【公众号设置】/【账号详情】下【认证情况】显示资质审核通过, 即可认定为认证号.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">AppId</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="appid" value="{{$wechat['appid']}}">
                        <span class="help-block">请填写微信公众平台后台的AppId</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">AppSecret</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="appsecret" value="{{$wechat['appsecret']}}">
                        <span class="help-block">请填写微信公众平台后台的AppSecret, 只有填写这两项才能管理自定义菜单</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Oauth 2.0</label>

                    <div class="col-sm-10">
                        <p class="form-control-static">在微信公众号请求用户网页授权之前，开发者需要先到公众平台网站的【开发者中心】网页服务中配置授权回调域名。</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-danger">接口地址</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" readonly="readonly" value="{{__ROOT__}}/index.php?s=site/api/deal&siteid={{$wechat['siteid']}}">
                        <span class="help-block">设置“公众平台接口”配置信息中的接口地址</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-danger">Token</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="token" readonly="" value="{{$wechat['token']}}">

                            <div class="input-group-btn">
                                <button onclick="updateToken(this)" class="btn btn-default" type="button">重新生成</button>
                            </div>
                        </div>
                        <span class="help-block">与公众平台接入设置值一致，必须为英文或者数字，长度为3到32个字符. 请妥善保管, Token 泄露将可能被窃取或篡改平台的操作数据.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-danger">EncodingAESKey</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="encodingaeskey" readonly="" value="{{$wechat['encodingaeskey']}}">

                            <div class="input-group-btn">
                                <button onclick="updateEncodingaeskey(this)" class="btn btn-default" type="button">重新生成</button>
                            </div>
                        </div>
                        <span class="help-block">与公众平台接入设置值一致，必须为英文或者数字，长度为3到32个字符. 请妥善保管, Token 泄露将可能被窃取或篡改平台的操作数据.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">二维码</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="qrcode" readonly="" value="{{$wechat['qrcode']}}">

                            <div class="input-group-btn">
                                <button onclick="upImage(this)" class="btn btn-default" type="button">选择图片</button>
                            </div>
                        </div>
                        <div class="input-group" style="margin-top:5px;">
                            <img src="{{$wechat['qrcode']?:'resource/images/nopic.jpg'}}" class="img-responsive img-thumbnail" width="150">
                            <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片">×</em>
                        </div>
                        <span class="help-block">只支持JPG图片</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">头像</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="icon" readonly="" value="{{$wechat['icon']}}">

                            <div class="input-group-btn">
                                <button onclick="upImage(this)" class="btn btn-default" type="button">选择图片</button>
                            </div>
                        </div>
                        <div class="input-group" style="margin-top:5px;">
                            <img src="{{$wechat['icon']?:'resource/images/nopic.jpg'}}" class="img-responsive img-thumbnail" width="150">
                            <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片">×</em>
                        </div>
                        <span class="help-block">只支持JPG图片</span>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">保存并测试连接</button>
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
</script>
<style>
    .nav li.normal {
        background: #eee;

    }

    .nav li.normal a, .nav li.normal a:active, .nav li.normal a:focus {
        border: none;
    }
</style>
