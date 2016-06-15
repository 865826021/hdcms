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
        var hdjs = {
            path: 'resource/hdjs',
            upimage: {
                upload: "http://localhost/hdcms/index.php?s=core/util/upImage", //上传地址
                list: "http://localhost/hdcms/index.php?s=core/util/getImageLists",//图片列表地址
                del: "http://localhost/hdcms/index.php?s=core/util/removeImage",//删除图片
            },
            kindeditor: {
                uploadJson: 'ab',
                fileManagerJson: 'aa'
            }
        }
    </script>
    <script src="http://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
    <script src="resource/hdjs/dist/hd.js"></script>
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
        <li><a href="#">首页</a></li>
        <li>网站列表</li>
    </ol>
    <div class="clearfix">
        <div class="input-group">
            <a href="" class="btn btn-primary"><i class="fa fa-plus"></i> 添加网站</a>
        </div>
    </div>
    <br/>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">筛选</h3>
        </div>
        <div class="panel-body">
            <form action="" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">搜索</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" class="form-control" ng-model="name">

                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-search"></i> 搜索
                                </button>
                                <button type="button" class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#">根据网站名称搜索</a></li>
                                    <li><a href="#">根据公众号中称搜索</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <input type='hidden' name='__TOKEN__' value='b46b896a7976123ea0220a6b12be7fef'/></form>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="col-xs-6">
                        套餐:
                    </div>
                    <div class="col-xs-6 text-right">
                        <a href="?s=article/index/index" class="text-info">
                            <strong><i class="fa fa-cog"></i> 管理站点</strong>
                        </a>
                    </div>
                </div>
                <div class="panel-body clearfix">
                    <div class="col-xs-4 col-md-1">
                        <i class="fa fa-rss fa-4x"></i>
                    </div>
                    <div class="col-xs-4 col-md-5">
                        <h3>后盾网</h3>
                    </div>
                    <div class="col-xs-4 col-md-6 text-right">
                        <a href=""><i class="fa fa-check-circle fa-2x text-success"></i></a>
                        <!--                                <a href=""><i class="fa fa-times-circle fa-2x text-warning"></i></a>-->
                    </div>
                </div>

                <div class="panel-footer clearfix">
                    <div class="col-xs-6">
                        服务有效期 : 未设置
                    </div>
                    <div class="col-xs-6 text-right">
                        <a href=""><i class="fa fa-comments"></i> 设置公众号</a>&nbsp;&nbsp;&nbsp;
                        <a href=""><i class="fa fa-key"></i> 设置权限</a>&nbsp;&nbsp;&nbsp;
                        <a href=""><i class="fa fa-user"></i> 操作员管理</a>&nbsp;&nbsp;&nbsp;
                        <a href=""><i class="fa fa-trash"></i> 删除</a>&nbsp;&nbsp;&nbsp;
                        <a href=""><i class="fa fa-pencil-square-o"></i> 编辑</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        require(['app'],function(app){
            app.controller('ctrl',['$scope',function($scope){
            }])
        })
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


