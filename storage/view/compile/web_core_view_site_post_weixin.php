<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS - 免费开源多站点管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
    <script src="resource/hdjs/app/util.js"></script>
    <script src="resource/hdjs/require.js"></script>
    <script src="resource/hdjs/app/config.js"></script>
    <script src="../../../web/common/js/common.js"></script>
    <link href="../../../web/common/css/common.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        window.system = {
            attachment: "http://localhost/hdcms/attachment",
            user:{"uid":"1","groupid":"-1","username":"admin","security":"482b35e615","status":"1","regtime":"0","regip":"","lasttime":"1457375397","lastip":"0.0.0.0","starttime":"0","endtime":"1448975100","remark":"gggggggggg","role":"\u7cfb\u7edf\u7ba1\u7406\u5458"},
            site: [],
            root: "http://localhost/hdcms",
            url:"http://localhost/hdcms/index.php?s=core/site/post&step=2&siteid=10",
        }
    </script>
    <script>
        if (navigator.appName == 'Microsoft Internet Explorer') {
            if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
                alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
            }
        }
    </script>
</head>
<body class="hdcms">
                
    <div class="container-fluid admin-top ">
        <!--导航-->
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="?s=core/site/lists"><i class="fa fa-w fa-cogs"></i> 系统管理</a>
                        </li>
                        <li>
                            <a href="http://www.hdphp.com/doc" target="_blank"><i class="fa fa-w fa-file-code-o"></i> 在线文档</a>
                        </li>
                        <li>
                            <a href="http://bbs.houdunwang.com" target="_blank"><i class="fa fa-w fa-forumbee"></i> 后盾论坛</a>
                        </li>
                        <li>
                            <a href="http://www.houdunwang.com" target="_blank"><i class="fa fa-w fa-phone-square"></i> 联系后盾</a>
                        </li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; " aria-expanded="false">
                                <i class="fa fa-group"></i> 后盾网 <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="" target="_blank"><i class="fa fa-weixin fa-fw"></i> 编辑当前账号资料</a></li>
                                <li><a href="" target="_blank"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                <i class="fa fa-w fa-user"></i>
                                admin(系统管理员)
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">修改密码</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="http://localhost/hdcms/index.php?s=core/login/out">退出</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--导航end-->
    </div>

               <div class="container-fluid  system-container">
    <div class="container-fluid" style="margin-top: 30px;margin-bottom: 20px;">
        <div class="col-md-6" style="background: url('../../../web/common/images/logo.png') no-repeat;background-size: contain;height: 60px;opacity: .9;">
        </div>
        <div class="col-md-6">
            <ul class="nav nav-pills pull-right">
                <li>
                    <a href="?s=core/site/lists" class="tile ">
                    <i class="fa fa-sitemap fa-2x"></i>网站管理
                    </a>
                </li>
                <li>
                    <a href="?s=core/system/index" class="tile ">
                    <i class="fa fa-support fa-2x"></i>系统设置
                    </a>
                </li>
                <li>
                    <a href="?s=core/login/out" class="tile">
                        <i class="fa fa-sign-out fa-2x"></i>退出
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="well clearfix">
        
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="<?php echo u('system/index')?>">系统</a></li>
        <li class="active">设置公众号基本信息</li>
    </ol>
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation" class="normal"><a href="javascript:;">设置网站信息</a></li>
        <li role="presentation" class="active"><a href="javascript:;">设置公众号信息</a></li>
        <li role="presentation" class="normal"><a href="javascript:;">设置权限</a></li>
        <li role="presentation" class="normal"><a href="javascript:;">微信平台设置信息</a></li>
    </ul>
    <form action="" method="post" role="form" class="form-horizontal">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">设置公众号基本信息</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label star">公众号名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="wename" class="form-control">
                        <span class="help-block">填写公众号的帐号名称</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">微信号</label>

                    <div class="col-sm-10">
                        <input type="text" name="account" class="form-control">
                        <span class="help-block">填写公众号的帐号，一般为英文帐号</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">原始ID</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="original" placeholder="">
                        <span class="help-block">在给粉丝发送客服消息时,原始ID不能为空。建议您完善该选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">级别</label>

                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" name="level" value="1" checked> 普通订阅号
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="2"> 普通服务号
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="3"> 认证订阅号
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="level" value="4"> 认证服务号/认证媒体/政府订阅号
                        </label>
                        <span class="help-block">注意：即使公众平台显示为“未认证”, 但只要【公众号设置】/【账号详情】下【认证情况】显示资质审核通过, 即可认定为认证号.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">AppId</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="appid" placeholder="">
                        <span class="help-block">请填写微信公众平台后台的AppId</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">AppSecret</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="appsecret" placeholder="">
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
                    <label class="col-sm-2 control-label">二维码</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="qrcode" readonly>

                            <div class="input-group-btn">
                                <button onclick="upImage(this)" class="btn btn-default" type="button">选择图片</button>
                            </div>
                        </div>
                        <div class="input-group" style="margin-top:5px;">
                            <img src="resource/hdjs/images/upload-pic.jpg" class="img-responsive img-thumbnail" width="150" id="thumb">
                            <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片">×</em>
                        </div>
                        <span class="help-block">只支持JPG图片</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">头像</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="icon" readonly>

                            <div class="input-group-btn">
                                <button onclick="upImage(this)" class="btn btn-default" type="button">选择图片</button>
                            </div>
                        </div>
                        <div class="input-group" style="margin-top:5px;">
                            <img src="resource/hdjs/images/upload-pic.jpg" class="img-responsive img-thumbnail" width="150" id="thumb">
                            <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片">×</em>
                        </div>
                        <span class="help-block">只支持JPG图片</span>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">下一步</button>
    <input type='hidden' name='__TOKEN__' value='ff3892e34a4b73bc8db4c2ff1b91d0f1'/></form>


    </div>
    <div class="text-muted footer">
        <a href="http://www.houdunwang.com">高端培训</a>
        <a href="http://www.hdphp.com">开源框架</a>
        <a href="http://bbs.houdunwang.com">后盾论坛</a>
        <br/>
        Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
    </div>
</div>
</body>
</html>


<script>
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
    $('form').submit(function () {
        var msg = '';
        var versionCode = $.trim($('[name="wename"]').val());
        if (versionCode == '') {
            msg += '公众号名称不能为空<br/>';
        }
        if (msg) {
            require(['util'], function (util) {
                util.message(msg,'','error');
            });
            return false;
        }
    })
</script>
<style>
    .nav li.normal {
        background: #eee;

    }

    .nav li.normal a, .nav li.normal a:active, .nav li.normal a:focus {
        border: none;
    }
</style>
