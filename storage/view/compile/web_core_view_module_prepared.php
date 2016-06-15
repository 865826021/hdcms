<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS - 免费开源多站点管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../../web/common/css/common.css" rel="stylesheet">
    <script>
//        var hdjs = {
//            path: 'resource/hdjs',
//            upimage: {
//                upload: "http://localhost/hdcms/index.php?s=core/util/upImage", //上传地址
//                list: "http://localhost/hdcms/index.php?s=core/util/getImageLists",//图片列表地址
//                del: "http://localhost/hdcms/index.php?s=core/util/removeImage",//删除图片
//            },
//            kindeditor: {
//                uploadJson: 'ab',
//                fileManagerJson: 'aa'
//            }
//        }
    </script>
    <script src="http://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
    <script src="resource/hdjs/dist/hdjs.js"></script>
    <script src="../../../web/common/js/common.js"></script>
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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
                        <a href="http://localhost/hdcms/index.php?s=core/index/index"><i class="fa fa-w fa-cogs"></i> 系统管理</a>
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
                    <a href="http://localhost/hdcms/index.php?s=core/index/index" class="tile ">
                        <i class="fa fa-sitemap fa-2x"></i>网站管理
                    </a>
                </li>
                <li>
                    <a href="http://localhost/hdcms/index.php?s=core/system/index" class="tile ">
                        <i class="fa fa-support fa-2x"></i>系统设置
                    </a>
                </li>
                <li>
                    <a href="http://localhost/hdcms/index.php?s=core/login/out" class="tile">
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
        <li class="active">已经安装模块</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="<?php echo u('installed')?>">已经安装模块</a></li>
        <li role="presentation" class="active"><a href="?s=core/module/prepared">安装模块</a></li>
        <li role="presentation"><a href="?s=core/module/design">设计新模块</a></li>
        <li role="presentation"><a href="?s=core/module/store">应用商城</a></li>
    </ul>
    <h5 class="page-header">未安装的本地模块</h5>
    <?php foreach ((array)$locality as $local){?>
        <div class="media">
            <div class="pull-right">
                <div style="margin-right: 10px;">
                    <a href="<?php echo u('install',array('module'=>$local['name']))?>">安装模块</a>
                </div>
            </div>
            <div class="media-left">
                <a href="#">
                    <img class="media-object" src="<?php echo $local['cover']?>" style="width: 50px;height: 50px;">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading"><?php echo $local['title']?>
                    <small>（标识：<?php echo $local['name']?> 版本：<?php echo $local['version']?> 作者：<?php echo $local['author']?>）</small>
                </h4>
                <a href="javascript:;" class="detail">详细介绍</a>
            </div>
            <div class="alert alert-info" role="alert"><strong>功能介绍</strong>： <?php echo $local['detail']?></div>
        </div>
    <?php }?>
    <style>
        .media {
            border-bottom: solid 1px #dcdcdc;
            padding-bottom: 6px;
        }

        .media h4.media-heading {
            font-size: 16px;
        }

        .media .media-left a img {
            border-radius: 5px;
        }

        .media h4.media-heading small {
            font-size: 65%;
        }

        .media .media-body a {
            display: inline-block;
            font-size: 14px;
            padding-top: 6px;
        }

        .media .alert {
            margin-top: 6px;
            display: none;
        }
    </style>
    <script>
        $('.detail').click(function () {
            //隐藏所有详细介绍内容
            $('.detail').not(this).parent().next().hide();
            //显示介绍
            $(this).parent().next().toggle();
        });
    </script>

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

