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
        <li><a href="?s=core/system/index">系统</a></li>
        <li class="active">用户列表</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="<?php echo u('lists')?>">用户列表</a></li>
        <li role="presentation"><a href="<?php echo u('add')?>">添加用户</a></li>
    </ul>
    <form action="<?php echo u('remove')?>" class="form-horizontal" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">可使用的公众服务套餐</h3>
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="30">用户名</th>
                        <th>身份</th>
                        <th>状态</th>
                        <th>注册时间</th>
                        <th>服务开始时间 ~~ 结束时间</th>
                        <th width="50">操作</th>
                        <th width="100"></th>
                        <th width="75"></th>
                        <th width="75"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ((array)$users as $u){?>
                        <tr>
                            <td><?php echo $u['username']?></td>
                            <td>
                                <?php if($u['groupid']==-1){?>
                
                                    <span class="label label-success">管理员</span>
                                    <?php }else if($u['groupid']==0){?>
                                        <span class="label label-info">体验用户组</span>
                                        <?php }else{?>
                                        <span class="label label-info"><?php echo $u['name']?></span>
                                
               <?php }?>
                            </td>
                            <td>
                                <?php if($u['status']){?>
                
                                    <span class="label label-success">正常状态</span>
                                    <?php }else{?>
                                    <span class="label label-danger">被禁止</span>
                                
               <?php }?>
                            </td>
                            <td><?php echo date('Y-m-d h:i:s',$u['regtime'])?></td>
                            <td>
                                <?php echo date('Y-m-d',$u['starttime'])?> ~~
                                <?php if($u['endtime']){?>
                
                                    <?php echo date('Y-m-d',$u['endtime'])?>
                                    <?php }else{?>
                                    永久有效
                                
               <?php }?>
                            </td>
                            <td>
                                <a href="<?php echo u('edit',array('uid'=>$u['uid']))?>">编辑</a>
                            </td>
                            <?php if($u['groupid']==-1){?>
                
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php }else{?>
                                <td>
                                    <a href="<?php echo u('permission',array('uid'=>$u['uid']))?>">查看操作权限</a>
                                </td>
                                <td>
                                    <?php if($u['status']){?>
                
                                        <a href="javascript:;" onclick="updateStatus(<?php echo $u['uid']?>,0)">禁止用户</a>
                                        <?php }else{?>
                                        <a href="javascript:;" onclick="updateStatus(<?php echo $u['uid']?>,1)">启用用户</a>
                                    
               <?php }?>
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="removeUser('<?php echo $u['username']?>')">删除用户</a>
                                </td>
                            
               <?php }?>

                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <button class="btn btn-primary">提交</button>
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
    //锁定或解锁用户
    function updateStatus(uid, status) {
        require(['dialog'], function (dialog) {
            dialog.confirm({
                message: '确认要禁用/解禁此用户吗? ',
                callback: function (state) {
                    if (state) {
                        url = "<?php echo u('updateStatus')?>&uid=" + uid + "&status=" + status;
                        $.get(url, function () {
                            location.reload(true);
                        })
                    }
                }
            });
        });
    }

    //删除用户
    function removeUser(username) {
        require(['dialog'], function (dialog) {
            dialog.confirm({
                message: '确定删除 [' + username + '] 用户吗? ',
                callback: function (state) {
                    if (state) {
                        $.post("<?php echo u('remove')?>", {username: username}, function () {
                            location.reload(true);
                        })
                    }
                }
            });
        });
    }
</script>
