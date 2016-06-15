<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS - 免费开源多站点管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../../web/common/css/common.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
    <script src="resource/hdjs/app/util.js"></script>
    <script src="resource/hdjs/require.js"></script>
    <script src="resource/hdjs/app/config.js"></script>
    <script src="../../../web/common/js/common.js"></script>
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
        
    <?php if(!IS_AJAX){?>
                
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i></li>
            <li><a href="<?php echo u('system/index')?>">系统</a></li>
            <li class="active">添加用户</li>
        </ol>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php echo u('lists')?>">用户列表</a></li>
            <li role="presentation" class="active"><a href="<?php echo u('add')?>">添加用户</a></li>
        </ul>

        <h5 class="page-header">添加新用户</h5>
    
               <?php }?>
    <form action="<?php echo __URL__?>" class="form-horizontal ajaxfrom" method="post">
        <div class="form-group">
            <label class="col-sm-2 control-label">用户名</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" name="username">
                <span id="helpBlock" class="help-block">请输入用户名，用户名为 3 到 15 个字符组成，包括汉字，大小写字母（不区分大小写）</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">密码</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="password">
                <span class="help-block">请填写密码，最小长度为 6 个字符</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">确认密码</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="password2">
                <span class="help-block">重复输入密码，确认正确输入</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">所属用户组</label>

            <div class="col-sm-10">
                <select class="js-example-basic-single form-control" name="groupid">
                    <option value="0">请选择所属用户组</option>
                    <?php foreach ((array)$groups as $g){?>
                        <option value="<?php echo $g['id']?>"><?php echo $g['name']?></option>
                    <?php }?>
                </select>
                <span class="help-block">分配用户所属用户组后，该用户会自动拥有此用户组内的模块操作权限</span>
                <span class="help-block">
                    <strong class="text-danger">设置用户组后，系统会根据对应用户组的服务期限对用户的服务开始时间和结束时间进行初始化</strong>
                </span>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">备注</label>

            <div class="col-sm-10">
                <textarea class="form-control" rows="3" name="remark"></textarea>
            </div>
        </div>
        <button class="btn btn-primary col-sm-offset-2">确定注册</button>
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
    function addImage() {
        require(['util'], function (util) {
            util.image(function (images) {
                console.log(images);
            }, {
                multiple:false,//是否允许多图上传

            })
        })
    }
</script>
