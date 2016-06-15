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
            url:"http://localhost/hdcms/index.php?s=core/site/post&step=3&siteid=9&weid=9",
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
        <li class="active">设置站点权限</li>
    </ol>
    <?php if(!isset($_GET['from'])){?>
                
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation" class="normal"><a href="javascript:;">设置网站信息</a></li>
        <li role="presentation" class="normal"><a href="javascript:;">设置公众号信息</a></li>
        <li role="presentation" class="active"><a href="javascript:;">设置权限</a></li>
        <li role="presentation" class="normal"><a href="javascript:;">微信平台设置信息</a></li>
    </ul>
    
               <?php }?>
    <form action="" method="post" role="form" class="form-horizontal">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">站点权限设置</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label star">附件空间大小</label>

                    <div class="col-sm-10">
                        <input type="text" name="allfilesize" class="form-control" value="1000">
                        <span class="help-block">站点上传文件允许的总大小,单位MB</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">网站站长</label>

                    <div class="col-sm-10">
                        <span class="label label-success" id="admin_user">&nbsp;</span>
                        <input type="hidden" name="admin_id" value="0">
                        <a href="javascript:;" onclick="selectUser()">选择用户</a> -
                        如果是新用户请先<a href="javascript:;" onclick="addUser()">添加</a>
                        <span class="help-block">一个公众号只可拥有一个主管理员，管理员有管理公众号和添加操作员的权限
未指定主管理员时将默认属于创始人，则公众号具有所有模块及模板权限</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">公众号可使用的权限（以下选中套餐）</h4>
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>公众服务套餐</th>
                        <th>模块权限</th>
                        <th>模板权限</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ((array)$packages as $p){?>
                        <tr>
                            <td>
                                <input type="checkbox" name="package_id[]" value="<?php echo $p['id']?>">
                            </td>
                            <td><?php echo $p['name']?></td>
                            <td>
                                <span class="label label-success">系统模块</span>
                                <?php foreach ((array)$p['modules'] as $m){?>
                                    <span class="label label-info"><?php echo $m['title']?></span>
                                <?php }?>
                            </td>
                            <td>
                                <span class="label label-success">微站默认模板</span>
                            </td>
                        </tr>
                    <?php }?>
                    <tr>
                        <td></td>
                        <td>专属附加权限</td>
                        <td id="extModule"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <button type="button" class="btn btn-primary" onclick="extModules()">附加权限</button>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if(isset($_GET['from'])){?>
                
            <button type="submit" class="btn btn-primary">保存</button>
            <?php }else{?>
        <button type="submit" class="btn btn-primary">下一步</button>
        
               <?php }?>
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
    //选择管理员
    function selectUser() {
        require(['util'], function (util) {
            //user 选中用户id 数组类型
            util.modal({
                title: '选择用户',
                width: 700,
                modalId: 'getSingleUser',
                content: ['?s=core/common/users&single=1'],
                events: {
                    'hidden.bs.modal': function () {
                        var bt = $("#getSingleUser").find("button[class*='primary']");
                        var username = bt.attr('username');
                        var uid = bt.attr('uid');
                        $("#admin_user").text(username);
                        $("[name='admin_id']").val(uid);
                        $("#getSingleUser").remove();

                    }
                }
            })
        })
    }

    //添加用户
    function addUser() {
        util.ajaxShow('?s=core/user/add', {
            title: '会员注册',
            width: 700
        })
    }

    //专属附加权限
    function extModules() {
        require(['util'], function (util) {
            var modalobj = util.modal({
                modalId: 'modalList',
                content: ['?s=core/common/ajaxModules'],
                footer: '<button class="btn btn-primary confirm">确定</button>',
                events: {
                    confirm: function () {
                        //选取模块
                        var mH = '';
                        $("#modalList").find('#module button.btn-primary').each(function (i) {
                            var title = $(this).parent().prev().prev().text();
                            var name = $(this).parent().prev().text();
                            mH += '<span class="label label-success">' + title + '</span>' +
                                '<input name="modules[]" value="'+$(this).attr('mid')+'" type="hidden"/>';
                        });
                        $("#extModule").html(mH);
                        modalobj.modal('hide');
                    }
                },

            })
        })
    }
</script>
<style>
    .nav li.normal {
        background: #eee;

    }

    .nav li.normal a, .nav li.normal a:active, .nav li.normal a:focus {
        border: none;
    }
</style>
