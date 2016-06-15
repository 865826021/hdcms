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
        <li><a href="<?php echo u('system/index')?>">系统</a></li>
        <li class="active">菜单列表</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#">菜单列表</a></li>
        <li role="presentation"><a href="#">模块菜单</a></li>
    </ul>
    <h5 class="page-header">菜单列表</h5>

    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        系统内置的菜单不允许修改 "链接地址"，不允许切换 "显示状态", 不允许 "删除"
    </div>
    <form action="" method="post">
        <div class="panel panel-default" ng-controller="ctrl">
            <div class="panel-body table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="80">排序</th>
                        <th width="300">名称</th>
                        <th width="120">标识</th>
                        <th width="180">图标</th>
                        <th width="180">链接地址</th>
                        <th>显示</th>
                        <th>系统</th>
                        <th width="180">子链接</th>
                        <th width="50">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ((array)$data as $key=>$d){?>
                        <?php if ($d['_level'] <> 3 && $key > 0 && $data[$key - 1]['_level'] == 3) : ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="9">
                                    <div class="pill-bottom" style="margin-left:50px;" ng-click="addMenu($event)">
                                        &nbsp;&nbsp;<i class="fa fa-plus-circle"></i> 添加菜单
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($d['_level'] == 1 && $key > 0): ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="9">
                                    <div class="pill-bottom" ng-click="addTopMenuGroup($event)">
                                        &nbsp;&nbsp;<i class="fa fa-plus-circle"></i> 添加菜单组
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr level="<?php echo $d['_level']?>">
                            <input type="hidden" name="menu[<?php echo $d['id']?>][id]" value="<?php echo $d['id']?>">
                            <td>
                                <input type="text" name="menu[<?php echo $d['id']?>][orderby]" class="form-control"
                                       value="<?php echo $d['orderby']?>">
                            </td>
                            <td>
                                <?php if($d['_level']==1){?>
                
                                    <input type="text" name="menu[<?php echo $d['id']?>][title]" class="form-control"
                                           value="<?php echo $d['title']?>" style="width:150px;">
                                
               <?php }?>
                                <?php if($d['_level']==2){?>
                
                                    <div class="pill">
                                        <input type="text" name="menu[<?php echo $d['id']?>][title]" class="form-control"
                                               value="<?php echo $d['title']?>" style="width:150px;">
                                    </div>
                                
               <?php }?>
                                <?php if($d['_level']==3){?>
                
                                    <div class="pill" style="margin-left: 50px;">
                                        <input type="text" name="menu[<?php echo $d['id']?>][title]" class="form-control"
                                               value="<?php echo $d['title']?>" style="width:150px;">
                                    </div>
                                
               <?php }?>
                            </td>
                            <td><?php echo $d['name']?></td>
                            <td>
                                <?php if ($d['_level'] == 1): ?>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="menu[<?php echo $d['id']?>][icon]"
                                               value="<?php echo $d['icon']?>">
                                        <span class="input-group-addon"><i class="<?php echo $d['icon']?>"></i></span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default ico-modal" type="button">图标</button>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $d['url']?></td>
                            <td>
                                <?php if($d['is_display']){?>
                
                                    <span class="btn btn-success" title="点击切换" onclick="changeDisplayStatus(<?php echo $d['id']?>,0)">显示</span>
                                    <?php }else{?>
                                    <span class="btn btn-danger" title="点击切换" onclick="changeDisplayStatus(<?php echo $d['id']?>,1)">隐藏</span>
                                
               <?php }?>

                            </td>
                            <td>
                                <?php if($d['is_system']){?>
                
                                    <span class="label label-danger">系统内置</span>
                                    <?php }else{?>
                                    <span class="label label-success">自定义</span>
                                
               <?php }?>
                            </td>
                            <td><?php echo $d['append_url']?></td>
                            <td>
                                <?php if(!$d['is_system']){?>
                
                                    <span class="btn btn-danger system" title="删除菜单" ng-click="delMenu(<?php echo $d['id']?>)">删除</span>
                                
               <?php }?>
                            </td>
                        </tr>

                    <?php }?>
                    <?php if (count($data) - 1 == $key && $d['_level'] <>1) : ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="9">
                                <div class="pill-bottom" style="margin-left:50px;" ng-click="addMenu($event)">
                                    &nbsp;&nbsp;<i class="fa fa-plus-circle"></i> 添加菜单
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if (count($data) - 1 == $key) : ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="9">
                                <div class="pill-bottom" ng-click="addTopMenuGroup($event)">
                                    &nbsp;&nbsp;<i class="fa fa-plus-circle"></i> 添加菜单组
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="9">
                            <div class="add add_level0" ng-click="addTopMenu($event)"><i class="fa fa-plus-circle"></i> 添加顶级分类</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
    <input type='hidden' name='__TOKEN__' value='ad1805af846745b13469cceefd9b1e5e'/></form>

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
    //显示字体模态框
    require(['util'], function (util) {
        $("table").delegate('.ico-modal', 'click', function () {
            var div = $(this).parents('div').eq(0);
            util.font(function (ico) {
                div.find('input').val(ico);
                div.find('i').attr('class', ico);
            });
        });
    })

    //更改显示状态
    function changeDisplayStatus(id, state) {
        $.post("<?php echo u('changeDisplayState')?>", {id: id, is_display: state}, function () {
            location.reload(true);
        })
    }

    require(['app'], function (app) {
        app.controller('ctrl', ['$scope', function ($scope) {
            //添加顶级菜单
            $scope.addTopMenu = function ($event) {
                var currentTr = $($event.target).parents('tr').eq(0);
                var html = '<tr>\
                    <td>\
                    <input type="text" name="top_menu_order[]" class="form-control" value="0">\
                    </td>\
                    <td>\
                    <input type="text" name="top_menu_title[]" class="form-control" placeholder="名称" style="width:150px;">\
                    </td>\
                    <td>\
                    <input type="text" name="top_menu_name[]" class="form-control" placeholder="标识">\
                    </td>\
                    <td>\
                    <div class="input-group">\
                    <input type="text" class="form-control" name="top_menu_icon[]" value="fa fa-arrows">\
                    <span class="input-group-addon"><i class="fa fa-arrows"></i></span>\
                    <span class="input-group-btn">\
                    <button class="btn btn-default ico-modal" type="button">图标</button>\
                    </span>\
                    </div>\
                    </td>\
                    <td><i class="fa fa-times-circle" onclick="$(this).parents(\'tr\').remove()"></i></td>\
                    <td colspan="5">&nbsp;</td>\
                </tr>';
                currentTr.before(html);
            }
            //添加菜单组
            $scope.addTopMenuGroup = function ($event) {
                var currentTr = $($event.target).parents('tr').eq(0);
                //标识
                var name = currentTr.prevAll('tr[level=1]').eq(0).find('td').eq(2).text();
                //pid
                var pid = currentTr.prevAll('tr[level=1]').eq(0).find('input').eq(0).val();
                var html = '<tr>\
                <input type="hidden" name="menu_group_pid[]" value="' + pid + '"/>\
                <td>\
                <input type="text" name="menu_group_order[]" class="form-control" value="0">\
                </td>\
                <td>\
                <div class="pill">\
                <input type="text" name="menu_group_title[]" class="form-control" placeholder="名称" style="width:150px;">\
                </div>\
                </td>\
                <td>' + name + '</td>\
                <td>\
                <i class="fa fa-times-circle" onclick="$(this).parents(\'tr\').remove()"></i>\
                </td>\
                <td colspan="6"></td>\
                </tr>';
                currentTr.before(html);
            };
            //添加菜单
            $scope.addMenu = function ($event) {
                var currentTr = $($event.target).parents('tr').eq(0);
                //标识
                var name = currentTr.prevAll('tr[level=2]').eq(0).find('td').eq(2).text();
                //pid
                var pid = currentTr.prevAll('tr[level=2]').eq(0).find('input').eq(0).val();
                var html = '<tr>\
                <input type="hidden" name="son_menu_pid[]" value="' + pid + '"/>\
                <td>\
                <input type="text" name="son_menu_order[]" class="form-control" value="0">\
                </td>\
                <td>\
                <div class="pill" style="margin-left: 50px;">\
                <input type="text" name="son_menu_title[]" class="form-control" placeholder="名称" style="width:150px;">\
                </div>\
                </td>\
                <td>' + name + '</td>\
                <td>&nbsp;</td>\
                <td>\
                    <input type="text" name="son_menu_url[]" class="form-control" placeholder="链接"/>\
                </td>\
                <td>&nbsp;</td>\
                <td>&nbsp;</td>\
                <td>\
                    <input type="text" name="son_menu_append_url[]" class="form-control" placeholder="子菜单链接"/>\
                </td>\
                <td>\
                <i class="fa fa-times-circle" onclick="$(this).parents(\'tr\').remove()"></i>\
                </td>\
                <td colspan="6"></td>\
                </tr>';
                currentTr.before(html);
            };

            //删除菜单
            $scope.delMenu = function (id) {
                if (confirm('确定删除菜单吗?')) {
                    $.post("<?php echo u('delMenu')?>", {id: id}, function () {
                        location.reload(true);
                    })
                }
            }
        }]);

    });
</script>