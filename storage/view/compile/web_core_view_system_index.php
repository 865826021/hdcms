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
            url:"http://localhost/hdcms/index.php?s=core/system/index",
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
    </ol>
    <h5 class="page-header">云服务</h5>

    <div class="clearfix">
        <a href="" class="tile img-rounded">
            <i class="fa fa-cloud-download"></i>
            <span>一键更新</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-globe"></i>
            <span>云帐号</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-user-md"></i>
            <span>云服务诊断</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-shopping-bag"></i>
            <span>云商城</span>
        </a>
    </div>

    <h5 class="page-header">扩展</h5>

    <div class="clearfix">
        <a href="<?php echo u('core/module/installed')?>" class="tile img-rounded">
            <i class="fa fa-cubes"></i>
            <span>模块</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-volume-up"></i>
            <span>订阅管理</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-glass"></i>
            <span>常用服务</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-puzzle-piece"></i>
            <span>后台风格</span>
        </a>
    </div>

    <h5 class="page-header">系统管理</h5>

    <div class="clearfix">
        <a href="<?php echo u('menu/edit')?>" class="tile img-rounded">
            <i class="fa fa-list"></i>
            <span>系统菜单</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-refresh"></i>
            <span>更新缓存</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-folder-open"></i>
            <span>附件设置</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-exclamation"></i>
            <span>系统信息</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-book"></i>
            <span>查看日志</span>
        </a>
    </div>

    <h5 class="page-header">用户管理</h5>

    <div class="clearfix">
        <a href="" class="tile img-rounded">
            <i class="fa fa-briefcase"></i>
            <span>我的帐户</span>
        </a>
        <a href="<?php echo u('core/user/lists')?>" class="tile img-rounded">
            <i class="fa fa-user"></i>
            <span>用户管理</span>
        </a>
        <a href="<?php echo u('core/group/lists')?>" class="tile img-rounded">
            <i class="fa fa-users"></i>
            <span>用户组管理</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-user-md"></i>
            <span>用户设置</span>
        </a>

    </div>

    <h5 class="page-header">站点管理</h5>

    <div class="clearfix">
        <a href="" class="tile img-rounded">
            <i class="fa fa-sitemap"></i>
            <span>站点列表</span>
        </a>
        <a href="<?php echo u('core/package/lists')?>" class="tile img-rounded">
            <i class="fa fa-comments-o"></i>
            <span>服务套餐</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-inbox"></i>
            <span>站点设置</span>
        </a>
    </div>



    <h5 class="page-header">系统工具</h5>

    <div class="clearfix">
        <a href="" class="tile img-rounded">
            <i class="fa fa-database"></i>
            <span>数据库</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-legal"></i>
            <span>木马查杀</span>
        </a>
        <a href="" class="tile img-rounded">
            <i class="fa fa-file"></i>
            <span>文件校验</span>
        </a>
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

