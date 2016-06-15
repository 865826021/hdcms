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
        window.sys = {
            attachment: "http://localhost/hdcms/attachment",
            user:{"uid":"1","groupid":"-1","username":"admin","security":"482b35e615","status":"1","regtime":"0","regip":"","lasttime":"1457375397","lastip":"0.0.0.0","starttime":"0","endtime":"1448975100","remark":"gggggggggg","role":"\u7cfb\u7edf\u7ba1\u7406\u5458"},
            site: [],
            root: "http://localhost/hdcms",
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
        <li class="active">已经安装模块</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="<?php echo u('lists')?>">服务套餐列表</a></li>
        <li role="presentation" class="active"><a href="<?php echo u('accretion')?>">添加套餐</a></li>
    </ul>
    <div class="clearfix">
        <div class="form-group">
            <form class="form-horizontal" action="" method="post">
                <h5 class="page-header">服务套餐管理</h5>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">服务套餐名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>

                <h5 class="page-header">设置当前用户允许访问的模块</h5>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="150">
                                    <input type="checkbox" id="selectAll">
                                    (全选)选择
                                </th>
                                <th>模块名称</th>
                                <th>模块标识</th>
                                <th>功能简介</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ((array)$modules as $p){?>
                                <tr>
                                    <td><input type="checkbox" name="name" checked disabled></td>
                                    <td><?php echo $p['title']?></td>
                                    <td><?php echo $p['name']?></td>
                                    <td><?php echo $p['resume']?></td>
                                    <td>
                                        <?php if($p['is_system']){?>
                
                                            <span class="label label-success">系统</span>
                                        
               <?php }?>
                                    </td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">确认</button>
                    </div>
                </div>
            <input type='hidden' name='__TOKEN__' value='ff3892e34a4b73bc8db4c2ff1b91d0f1'/></form>
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
