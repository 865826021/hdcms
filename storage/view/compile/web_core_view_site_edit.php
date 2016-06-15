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
            url:"http://localhost/hdcms/index.php?s=core/site/edit&siteid=7",
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
                        <input type="text" name="name" class="form-control" value="<?php echo $site['name']?>">
                        <span class="help-block">网站中显示的网站名称,可以使用中文等任意字符</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">网站关键字</label>

                    <div class="col-sm-10">
                        <textarea name="keywords" class="form-control" cols="30" rows="3"><?php echo $site['keywords']?></textarea>
                        <span class="help-block">
                            多个关键词用逗号分隔长度不能超过80个字符
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">网站描述</label>

                    <div class="col-sm-10">
                        <textarea name="description" class="form-control" cols="30" rows="3"><?php echo $site['description']?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">网站备案号</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="icp" value="<?php echo $site['icp']?>">
                        <span class="help-block">国内网站访问必须具有备案号,否则将可能受到封站的处理</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label star">网站域名</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="domain" value="<?php echo $site['domain']?>">
                        <span class="help-block">
                            网站域名用来在除了微信访问时识别网站唯一性的标识,多个域名用英文半角逗号进行分隔, 如: houdunwang.com,hdphp.com都是合法域名
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">排序</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="rank" value="<?php echo $site['rank']?>">
                        <span class="help-block">
                            当有相应域名在使用时的优先级,最大不能超过255数值越大优先级越高
                        </span>
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
                        <input type="text" name="wename" class="form-control" value="<?php echo $wechat['wename']?>">
                        <span class="help-block">填写公众号的帐号名称</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">微信号</label>

                    <div class="col-sm-10">
                        <input type="text" name="account" class="form-control" value="<?php echo $wechat['account']?>">
                        <span class="help-block">填写公众号的帐号，一般为英文帐号</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">原始ID</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="original" value="<?php echo $wechat['original']?>">
                        <span class="help-block">在给粉丝发送客服消息时,原始ID不能为空。建议您完善该选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">级别</label>

                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <?php if($wechat['level']==1){?>
                
                                <input type="radio" name="level" value="1" checked> 普通订阅号
                                <?php }else{?>
                                <input type="radio" name="level" value="1"> 普通订阅号
                            
               <?php }?>
                        </label>
                        <label class="radio-inline">
                            <?php if($wechat['level']==2){?>
                
                                <input type="radio" name="level" value="2" checked> 普通服务号
                                <?php }else{?>
                                <input type="radio" name="level" value="2"> 普通服务号
                            
               <?php }?>
                        </label>
                        <label class="radio-inline">
                            <?php if($wechat['level']==3){?>
                
                                <input type="radio" name="level" value="3" checked> 认证订阅号
                                <?php }else{?>
                                <input type="radio" name="level" value="3"> 认证订阅号
                            
               <?php }?>
                        </label>
                        <label class="radio-inline">
                            <?php if($wechat['level']==4){?>
                
                                <input type="radio" name="level" value="4" checked> 认证服务号/认证媒体/政府订阅号
                                <?php }else{?>
                                <input type="radio" name="level" value="4"> 认证服务号/认证媒体/政府订阅号
                            
               <?php }?>
                        </label>
                        <span class="help-block">注意：即使公众平台显示为“未认证”, 但只要【公众号设置】/【账号详情】下【认证情况】显示资质审核通过, 即可认定为认证号.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">AppId</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="appid" value="<?php echo $wechat['appid']?>">
                        <span class="help-block">请填写微信公众平台后台的AppId</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">AppSecret</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="appsecret" value="<?php echo $wechat['appsecret']?>">
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
                        <input type="text" class="form-control" readonly value="<?php echo __ROOT__?>/index.php?s=core/api/deal&id=<?php echo $wechat['siteid']?>">
                        <span class="help-block">设置“公众平台接口”配置信息中的接口地址</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-danger">Token</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="token" readonly="" value="<?php echo $wechat['token']?>">

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
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="encodingaeskey" readonly="" value="<?php echo $wechat['encodingaeskey']?>">

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
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="qrcode" readonly="" value="<?php echo $wechat['qrcode']?>">

                            <div class="input-group-btn">
                                <button onclick="upImage(this)" class="btn btn-default" type="button">选择图片</button>
                            </div>
                        </div>
                        <div class="input-group" style="margin-top:5px;">
                            <img src="<?php echo $wechat['qrcode']?>" class="img-responsive img-thumbnail" width="150">
                            <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片">×</em>
                        </div>
                        <span class="help-block">只支持JPG图片</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">头像</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid" name="icon" readonly="" value="<?php echo $wechat['icon']?>">

                            <div class="input-group-btn">
                                <button onclick="upImage(this)" class="btn btn-default" type="button">选择图片</button>
                            </div>
                        </div>
                        <div class="input-group" style="margin-top:5px;">
                            <img src="<?php echo $wechat['icon']?>" class="img-responsive img-thumbnail" width="150">
                            <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片">×</em>
                        </div>
                        <span class="help-block">只支持JPG图片</span>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
        <button type="button" class="btn btn-success" onclick="connectTest()">公众号连接测试</button>
        <span class="label label-info">先执行保存操作后, 才可以进行测试</span>
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
    function connectTest() {
        require(['util'], function (util) {
            $.get("?s=core/site/connect&siteid=<?php echo $_GET['siteid']?>", function (response) {
                var type = response.valid ? 'success' : 'error';
                util.message(response.message, '', type);
            }, 'json');
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
