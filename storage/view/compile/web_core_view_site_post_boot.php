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
            url:"http://localhost/hdcms/index.php?s=core/site/post&step=4&siteid=9&weid=9",
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
        <li class="active">接入到公众平台</li>
    </ol>
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation"><a href="javascript:;">设置网站信息</a></li>
        <li role="presentation"><a href="javascript:;">设置公众号信息</a></li>
        <li role="presentation"><a href="javascript:;">设置权限</a></li>
        <li role="presentation" class="active"><a href="javascript:;">微信平台设置信息</a></li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">接入到公众平台</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                您绑定的微信公众号：<strong class="text-danger"><?php echo $wechat['wename']?></strong>，请按照下列引导完成配置。
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <h5 class="page-header">登录 <a href="https://mp.weixin.qq.com/" class="text-danger">微信公众平台</a>，点击左侧菜单最后一项，进入 [ <span class="text-info">开发者中心</span> ]</h5>
                    <img src="../../../web/common/images/weichat/Snip20160308_5.png" class="img-thumbnail">

                    <h5># 如果您未成为开发者，请勾选页面上的同意协议，再点击 [ 成为开发者 ] 按钮</h5>
                </li>
                <li class="list-group-item">
                    <h5 class="page-header">在开发者中心，找到［ 服务器配置 ］栏目下URL和Token设置</h5>
                    <img src="../../../web/common/images/weichat/Snip20160308_8.png" class="img-thumbnail">

                    <h5># 将以下链接链接填入对应输入框：</h5>

                    <form action="" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">URL</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <a href="javascript:;" class="text-info copy">
                                        <?php echo __ROOT__?>/index.php?s=core/api/deal&id=<?php echo $wechat['siteid']?>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Token</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <a href="javascript:;" class="text-info copy"><?php echo $wechat['token']?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">EncodingAESKey</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <a href="javascript:;" class="text-info copy"><?php echo $wechat['encodingaeskey']?></a>
                                </p>
                            </div>
                        </div>
                        <p>请点击 [ <strong class="text-danger">启用</strong> ] ，以启用服务器配置：</p>
                    <input type='hidden' name='__TOKEN__' value='ff3892e34a4b73bc8db4c2ff1b91d0f1'/></form>
                </li>
                <li class="list-group-item">
                    <h5 class="page-header">公众号 <strong class="text-info"><?php echo $wechat['wename']?></strong> 正在等待接入……请及时按照以上步骤操作接入公众平台</h5>

                    <p>检查公众平台配</p>

                    <p>编辑公从号 <a href=""><?php echo $wechat['wename']?></a></p>
                    <button class="btn btn-success" onclick="checkConnect();">检测公众号是否接入成功</button>
                    <a href="" class="btn btn-primary">暂不接入,先去查看管理功能</a>
                    <a href="http://localhost/hdcms/index.php?s=core/site/lists" class="btn btn-info" href="">返回站点列表</a>
                </li>
            </ul>
        </div>
    </div>


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
    //检测公众号接入
    function checkConnect() {
        $.get('?s=core/site/checkConnect', function (res) {
            if (res.valid) {
                alert('接入成功');
            } else {
                alert('接入失败');
            }
        })
    }
    require(['util'], function (util) {
        $('.copy').each(function () {
            var This = this;
            util.zclip(This, $(This).text(), function () {
                $(This).next('span').remove().end().after('&nbsp;<span class="label label-success">复制成功</span>');
            });
        })

    });
</script>

<style>
    .nav li.normal {
        background: #eee;

    }

    .nav li.normal a, .nav li.normal a:active, .nav li.normal a:focus {
        border: none;
    }
</style>