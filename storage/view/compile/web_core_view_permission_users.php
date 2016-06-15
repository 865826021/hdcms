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
            url:"http://localhost/hdcms/index.php?s=core/permission/users&siteid=7",
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
        <li role="presentation" class="active"><a href="#">站点操作员列表</a></li>
    </ul>
    <h5 class="page-header">设置可操作用户</h5>

    <div class="alert alert-info" role="alert">
        <span class="fa fa-info-circle"></span>
        操作员不允许删除公众号和编辑公众号资料，管理员无此限制
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th width="60">选择</th>
                    <th width="120">用户编号</th>
                    <th width="200">用户名</th>
                    <th width="250">角色</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ((array)$users as $u){?>
                    <tr class="active">
                        <td>
                            <input type="checkbox" name="uid[]" value="<?php echo $u['uid']?>">
                        </td>
                        <td><?php echo $u['uid']?></td>
                        <td><?php echo $u['username']?></td>
                        <td>
                            <label class="radio-inline">
                                <?php if($u['role']=='operate'){?>
                
                                    <input type="radio" name="role[<?php echo $u['uid']?>]" value="operate" checked>操作员
                                    <?php }else{?>
                                    <input type="radio" name="role[<?php echo $u['uid']?>]" value="operate">操作员
                                
               <?php }?>
                            </label>
                            <label class="radio-inline">
                                <?php if($u['role']=='manage'){?>
                
                                    <input type="radio" name="role[<?php echo $u['uid']?>]" value="manage" checked>管理员
                                    <?php }else{?>
                                    <input type="radio" name="role[<?php echo $u['uid']?>]" value="manage">管理员
                                
               <?php }?>
                            </label>
                        </td>
                        <td>
                            <a href="?s=core/user/edit&uid=<?php echo $u['uid']?>">编辑用户</a>&nbsp;|&nbsp;
                            <a href="?s=core/permission/menu&siteid=<?php echo $_GET['siteid']?>&fromuid=<?php echo $u['uid']?>">设置权限</a>&nbsp;|&nbsp;
                            <a href="?s=core/user/permission&uid=<?php echo $u['uid']?>">查看操作权限</a>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
            <button class="btn btn-default" onclick="getUsers()">选择帐号操作员</button>
            <button class="btn btn-default" onclick="deleteUser()">删除选中帐号</button>
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
    //更改角色
    $(function () {
        $(":radio").change(function () {
            var role = $(this).val();
            var uid = $(this).parents('td').eq(0).prev().prev().text();
            $.post('?s=core/permission/changeRole', {role: role, uid: uid, siteid: "<?php echo $_GET['siteid']?>"}, function (response) {
                if (response.valid) {
                    util.message('角色类型修改成功', '', 'success');
                }
            }, 'json');
        });
    });

    //选择帐号操作员
    function getUsers() {
        require(['util'], function (util) {
            //user 选中用户id 数组类型
            modalobj = util.modal({
                title: '选择用户',
                width: 700,
                modalId: 'getSingleUser',
                content: ['?s=core/common/users&single=0'],
                events: {
                    'hidden.bs.modal': function () {
                        var bt = $("#getUsers").find("button[class*='primary']");
                        bt.each(function (i) {
                            uid = $(this).attr('uid');
                            if (uid) {
                                $.post('?s=core/permission/addOperator', {siteid: "<?php echo $_GET['siteid']?>", uid: uid}, function () {
                                    location.reload(true);
                                })
                            }
                        })
                        //删除模态
                        modalobj.remove();
                    }
                }
            })
        })
    }

    //删除帐号
    function deleteUser() {
        require(['util'], function (util) {
            util.modal({
                title: '系统提示',
                content: '确定从站点中删除选中的帐号吗?',
                footer: '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\
                <button type="button" class="btn btn-primary confirm" >确定</button>',
                events: {
                    confirm: function () {
                        var uids = [];
                        $("[name='uid[]']:checked").each(function (i) {
                            uids.push($(this).val());
                        });
                        $.post('?s=core/permission/removeSiteUser', {uids: uids, siteid: "<?php echo $_GET['siteid']?>"}, function () {
                            location.reload(true);
                        });
                    }
                }
            })
        });

    }
</script>
