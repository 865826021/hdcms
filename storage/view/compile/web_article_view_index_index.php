<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="http://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../web/common/css/common.css" rel="stylesheet">
    <script>
        var hdjs = {
            path: 'resource/hdjs',
            upimage: {
                upload: "http://localhost/hdcms/index.php?s=core/upload/upImage", //上传地址
                list: "http://localhost/hdcms/index.php?s=core/upload/getImageLists",//图片列表地址
                del: "http://localhost/hdcms/index.php?s=core/upload/remove",//删除图片
            },
            kindeditor:{
                uploadJson:'ab',
                fileManagerJson:'aa'
            }
        }
    </script>
    <script src="resource/hdjs/dist/hd.js"></script>
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
<body>
<div class="container-fluid admin-top">
    <!--导航-->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="?s=core/index/index"><i class="fa fa-reply-all"></i> 返回系统</a>
                    </li>
                    <li>
                        <a href="?s=cms/index/index"><i class="fa fa-w fa-file-code-o"></i> 文章系统</a>
                    </li>
                    <li>
                        <a href="?s="><i class="fa fa-w fa-comments"></i> 微信营销</a>
                    </li>
                    <li>
                        <a href="?s="><i class="fa fa-w fa-coffee"></i> 插件管理</a>
                    </li>
                    <li>
                        <a href="?s="><i class="fa fa-w fa-cogs"></i> 扩展功能</a>
                    </li>
                    <li>
                        <a href="http://doc.hdphp.com" target="_blank"><i class="fa fa-w fa-file-code-o"></i> 在线文档</a>
                    </li>
                    <li>
                        <a href="http://bbs.houdunwang.com" target="_blank"><i class="fa fa-w fa-forumbee"></i> 后盾论坛</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="fa fa-w fa-user"></i>
                            hdxj() <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">修改密码</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="javascript:out();">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--导航end-->
</div>
<!--主体-->
<div class="container-fluid admin_menu">
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-lg-2 left-menu">
            <div id="search-menu">
                <input class="form-control input-lg" type="text" ng-model="search_menu" placeholder="输入菜单名称可快速查找">
            </div>
            <div class="panel panel-default">
                                    </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            
    <h1>文章管理</h1>

        </div>
    </div>
</div>
<script>
    function out() {
        require(['jquery'], function ($) {
            $.get("?s=core/index/out", function () {
                location.href = "?s=core/index/index";
            })
        })
    }
</script>
<!--主体end-->
</body>
</html>

