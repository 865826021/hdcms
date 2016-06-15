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
        
    <div ng-controller="ctrl">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i></li>
            <li><a href="<?php echo u('system/index')?>">系统</a></li>
            <li class="active">已经安装模块</li>
        </ol>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="<?php echo u('installed')?>">已经安装模块</a></li>
            <li role="presentation"><a href="?s=core/module/prepared">安装模块</a></li>
            <li role="presentation"><a href="?s=core/module/design">设计新模块</a></li>
            <li role="presentation"><a href="?s=core/module/store">应用商城</a></li>
        </ul>
        <h5 class="page-header">菜单列表</h5>

        <nav role="navigation" class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">模块类型</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?php echo __URL__?>">全部</a></li>
                        <li><a href="#" data-type="business">主要业务</a></li>
                        <li><a href="#" data-type="customer">客户关系</a></li>
                        <li><a href="#" data-type="marketing">营销与活动</a></li>
                        <li><a href="#" data-type="tools">常用服务与工具</a></li>
                        <li><a href="#" data-type="industry">行业解决方案</a></li>
                        <li><a href="#" data-type="other">其他</a></li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="搜索模块" id="search">
                        </div>
                    <input type='hidden' name='__TOKEN__' value='c7f0747b31eddb0c712d5e7ccbd79e03'/></form>
                </div>
            </div>
        </nav>
        <?php foreach ((array)$modules as $m){?>
            <div class="media" type="<?php echo $m['industry']?>">
                <div class="pull-right">
                    <div style="margin-right: 10px;">
                        <?php if($m['type']==1){?>
                
                            <span class="label label-primary">系统内置模块</span>
                            <?php }else{?>
                            <span class="label label-success">本地安装模块</span>&nbsp;&nbsp;&nbsp;
                            <a href="javascript:;" ng-click="uninstall('<?php echo $m['name']?>','<?php echo $m['title']?>')">卸载</a>
                        
               <?php }?>
                    </div>
                </div>
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" src="<?php echo $m['cover']?>" style="width: 50px;height: 50px;"/>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading"><?php echo $m['title']?>
                        <small>（标识：<?php echo $m['name']?> 版本：<?php echo $m['version']?> 作者：<?php echo $m['author']?>）</small>
                    </h4>
                    <a href="javascript:;" class="detail">详细介绍</a>
                </div>
                <div class="alert alert-info" role="alert"><strong>功能介绍</strong>： <?php echo $m['detail']?></div>
            </div>
        <?php }?>
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
    //模块介绍
    $('.detail').click(function () {
        //隐藏所有详细介绍内容
        $('.detail').not(this).parent().next().hide();
        //显示介绍
        $(this).parent().next().toggle();
    });

    //点击模块类型显示列表
    $(".navbar-nav [data-type]").click(function () {
        //类型
        var dataType = $(this).attr('data-type');
        $(".media").show().not('[type=' + dataType + ']').hide();
        $('.navbar-nav').find('li').removeClass('active');
        $(this).parent().addClass('active');
    });

    $("#search").keyup(function () {
        var word = $(this).val();
        if (word == '') {
            //搜索词为空
            $(".media").show();
        } else {
            $(".media").each(function (i) {
                if ($(this).find('h4').text().indexOf(word) >= 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })
        }
    })

    require(['app', 'dialog'], function (app, dialog) {
        app.controller('ctrl', ['$scope', function ($scope) {
            //删除模块
            $scope.uninstall = function (name, title) {
                dialog.confirm({
                    message: '确定删除 ' + title + ' 模块吗?',
                    callback: function (state) {
                        if (state) {
                            location.href = '?s=core/module/uninstall&module=' + name;
                        }
                    }
                });
            }
        }])
    })
</script>
