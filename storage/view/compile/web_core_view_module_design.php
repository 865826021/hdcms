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
        
    <div class="clearfix">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i></li>
            <li><a href="<?php echo u('system/index')?>">系统</a></li>
            <li class="active">已经安装模块</li>
        </ol>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php echo u('installed')?>">已经安装模块</a></li>
            <li role="presentation"><a href="?s=core/module/prepared">安装模块</a></li>
            <li role="presentation" class="active"><a href="?s=core/module/design">设计新模块</a></li>
            <li role="presentation"><a href="?s=core/module/store">应用商城</a></li>
        </ul>
        <form action="" id="form" class="form-horizontal form" method="post" enctype="multipart/form-data">
            <h5 class="page-header">模块基本信息
                <small>这里来定义你自己模块的基本信息</small>
            </h5>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块名称</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="text" class="form-control" placeholder="" name="title">
                    <span class="help-block">模块的名称, 由于显示在用户的模块列表中. 不要超过10个字符 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块标识</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="text" class="form-control" placeholder="" name="name">
                    <span class="help-block">模块标识符, 应对应模块文件夹的名称, 系统按照此标识符查找模块定义, 只能由字母数字下划线组成 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">版本</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="text" class="form-control" placeholder="" name="version">
                    <span class="help-block">模块当前版本, 此版本号用于模块的版本更新 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块类型</label>

                <div class="col-sm-10 col-xs-12">
                    <select name="industry" class="form-control">
                        <option value="business">主要业务</option>
                        <option value="customer">客户关系</option>
                        <option value="marketing">营销与活动</option>
                        <option value="tools">常用服务与工具</option>
                        <option value="industry">行业解决方案</option>
                        <option value="other">其他</option>
                    </select>
                    <span class="help-block">模块的类型, 用于分类展示和查找你的模块 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块简述</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="text" class="form-control" placeholder="" name="resume">
                    <span class="help-block">模块功能描述, 使用简单的语言描述模块的作用, 来吸引用户 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块介绍</label>

                <div class="col-sm-10 col-xs-12">
                    <textarea name="detail" cols="30" rows="3" class="form-control"></textarea>
                    <span class="help-block">模块详细描述, 详细介绍模块的功能和使用方法 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">作者</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="text" class="form-control" placeholder="" name="author">
                    <span class="help-block">模块的作者, 留下你的大名吧 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">发布页</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="text" class="form-control" placeholder="" name="url" value="http://open.hdcms.com">
                    <span class="help-block">模块的发布页, 用于发布模块更新信息的页面, 推荐使用论坛模块版块 </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">设置项</label>

                <div class="col-sm-10 col-xs-12">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="setting" value="true">
                        存在全局设置项
                    </label>
                    <span class="help-block">此模块是否存在全局的配置参数, 此参数是针对公众账号独立保存的 </span>
                </div>
            </div>
            <h5 class="page-header">桌面应用设置
                <small>这里来定义公众平台消息相关处理</small>
            </h5>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">桌面入口导航</label>

                <div class="col-sm-10">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                        <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                            <span class="input-group-addon">操作名称</span>
                            <input class="form-control" name="bindings[web][title][]" type="text" placeholder="请输入操作名称">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                        <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                            <span class="input-group-addon">入口标识</span>
                            <input class="form-control" name="bindings[web][do][]" type="text" placeholder="请输入操作入口">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                        <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                            <span class="input-group-addon">操作附加数据</span>
                            <input class="form-control" name="bindings[web][data][]" type="text" placeholder="操作附加数据">
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-sm-offset-2">
                    <span class="help-block">桌面入口文件是PC端访问的入口标识</span>
                </div>
            </div>

            <div id="bindings-member">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">桌面个人中心导航</label>

                    <div class="col-sm-10">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作名称</span>
                                <input class="form-control" name="bindings[member][title][]" type="text" placeholder="请输入操作名称">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">入口标识</span>
                                <input class="form-control" name="bindings[member][do][]" type="text" placeholder="请输入操作入口">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作附加数据</span>
                                <input class="form-control" name="bindings[member][data][]" type="text" placeholder="操作附加数据">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <div class="well well-sm">
                        <a href="javascript:;" onclick="addFeature('member', '桌面个人中心导航');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
                    </div>
                    <span class="help-block">
                        在PC端的个人中心上显示相关功能的链接入口, 一般用于个人信息, 或针对个人的数据的展示.
                    </span>
                    <span class="help-block"><strong>注意: 微站个人中心导航扩展功能定义于 site 类中</strong></span>
                </div>
            </div>
            <h5 class="page-header">公众平台消息处理选项
                <small>这里来定义公众平台消息相关处理</small>
            </h5>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">订阅消息类型</label>

                <div class="col-md-10 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="text">文本消息(重要)
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="image">图片消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="voice">语音消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="video">视频消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="shortvideo">小视频消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="location">位置消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="link">链接消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="subscribe">粉丝开始关注
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="unsubscribe">粉丝取消关注
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="scan">扫描二维码
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="track">追踪地理位置
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="click">点击菜单(模拟关键字)
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscribes[]" value="view">点击菜单(链接)
                        </label>
                    </div>
                    <span class="help-block">订阅特定的消息类型后, 此消息类型的消息到达系统后将会以通知的方式(消息数据只读, 并不能返回处理结果)调用模块的接受器, 用这样的方式可以实现全局的数据统计分析等功能. 请参阅 <a href="http://www.we7.cc/docs/#flow-module-subscribe">模块消息订阅</a></span>

                    <div class="alert-warning alert">注意: 订阅的消息信息是只读的, 只能用作分析统计, 不能更改, 也不能改变处理主流程</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">直接处理消息</label>

                <div class="col-md-10 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="text">文本消息(重要)
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="image">图片消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="voice">语音消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="video">视频消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="shortvideo">小视频消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="location">位置消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="link">链接消息
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="subscribe">粉丝开始关注
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="unsubscribe">粉丝取消关注
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="scan">扫描二维码
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="track">追踪地理位置
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="click">点击菜单(模拟关键字)
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="processors[]" value="view">点击菜单(链接)
                        </label>
                    </div>
                    <span class="help-block">
                        当前模块能够直接处理的消息类型(没有上下文的对话语境, 能直接处理消息并返回数据). 如果公众平台传递过来的消息类型不在设定的类型列表中, 那么系统将不会把此消息路由至此模块
                    </span>

                    <div class="alert-warning alert">
                        注意: 关键字路由只能针对文本消息有效, 文本消息最为重要. 其他类型的消息并不能被直接理解, 多数情况需要使用文本消息来进行语境分析, 再处理其他相关消息类型
                        注意: 上下文锁定的模块不受此限制, 上下文锁定期间, 任何类型的消息都会路由至锁定模块
                    </div>
                </div>

            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">嵌入规则</label>

                <div class="col-sm-10 col-xs-12">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="rule" value="true">
                        需要嵌入规则
                    </label>
                    <span class="help-block">注意: 如果需要嵌入规则, 那么此模块必须能够处理文本类型消息 (需要定义Processor)</span>
                </div>
            </div>
            <h5 class="page-header">微站功能绑定
                <small>这里来定义此功能模块中微站的相关功能如何同系统对接</small>
            </h5>
            <div id="bindings-cover">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">功能封面</label>

                    <div class="col-sm-10">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作名称</span>
                                <input class="form-control" name="bindings[cover][title][]" type="text" placeholder="请输入操作名称">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">入口标识</span>
                                <input class="form-control" name="bindings[cover][do][]" type="text" placeholder="请输入操作入口">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作附加数据</span>
                                <input class="form-control" name="bindings[cover][data][]" type="text" placeholder="操作附加数据">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
                            <div style="margin-left:-15px;">
                                <label class="checkbox-inline" style="vertical-align:bottom">
                                    <input type="checkbox" name="bindings[cover][directly][]" value="false">无需登陆直接展示
                                </label> &nbsp; &nbsp; &nbsp;
                                <a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle" title="删除此操作"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <div class="well well-sm">
                        <a href="javascript:;" onclick="addFeature('cover', '功能封面');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
                    </div>
                    <span class="help-block">功能封面是定义微站里一个独立功能的入口(手机端操作), 将呈现为一个图文消息, 点击后进入微站系统中对应的功能.</span>
                    <span class="help-block"><strong>注意: 功能封面扩展功能定义于 WeSite 类的实现中</strong></span>
                </div>
            </div>

            <!--业务功能-->
            <div id="bindings-rule">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">规则列表</label>

                    <div class="col-sm-10">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作名称</span>
                                <input class="form-control" name="bindings[rule][title][]" type="text" placeholder="请输入操作名称">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">入口标识</span>
                                <input class="form-control" name="bindings[rule][do][]" type="text" placeholder="请输入操作入口">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作附加数据</span>
                                <input class="form-control" name="bindings[rule][data][]" type="text" placeholder="操作附加数据">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
                            <div style="margin-left:-15px;">
                                <label class="checkbox-inline" style="vertical-align:bottom">
                                    <input type="checkbox" name="bindings[rule][directly][]" value="false">无需登陆直接展示
                                </label> &nbsp; &nbsp; &nbsp;
                                <a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle" title="删除此操作"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <div class="well well-sm">
                        <a href="javascript:;" onclick="addFeature('rule', '规则列表');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
                    </div>
                    <span class="help-block">
                        规则列表是定义可重复使用或者可创建多次的活动的功能入口(管理后台Web操作), 每个活动对应一条规则. 一般呈现为图文消息, 点击后进入定义好的某次活动中.
                    </span>
                    <span class="help-block"><strong>注意: 规则列表扩展功能定义于 WeSite 类的实现中</strong></span>
                </div>
            </div>
            <!--业务功能-->
            <div id="bindings-business">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">业务功能</label>

                    <div class="col-sm-10">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作名称</span>
                                <input class="form-control" name="bindings[business][title][]" type="text" placeholder="请输入操作名称">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">入口标识</span>
                                <input class="form-control" name="bindings[business][do][]" type="text" placeholder="请输入操作入口">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作附加数据</span>
                                <input class="form-control" name="bindings[business][data][]" type="text" placeholder="操作附加数据">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">权限标识</label>

                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-11">
                    <textarea name="permission" cols="30" rows="6" class="form-control" placeholder="添加商品: shop_add"></textarea>
                    <span class="help-block">
                        如果您设计的模块需要权限设置，您可以在这里输入权限标识，并在对应的文件进行标识判断
权限标识由：标识名称和标识组成。例如,添加商品：shop_add"。标识格式：模块名称_标识。例如，模块名称为：shop,标识为：add,则对应标识为：shop_add
,多个权限标识使用换行隔开。
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <div class="well well-sm">
                        <a href="javascript:;" onclick="addFeature('business', '业务功能');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
                    </div>
                    <span class="help-block">
                        管理中心导航菜单将会在管理中心生成一个导航入口(管理后台Web操作), 用于对模块定义的内容进行管理.
                    </span>
                    <span class="help-block"><strong>注意: 管理中心导航菜单扩展功能定义于 WeSite 类的实现中</strong></span>
                </div>
            </div>
            <!--微站首页导航-->
            <div id="bindings-home">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">微站首页导航图标</label>

                    <div class="col-sm-10">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作名称</span>
                                <input class="form-control" name="bindings[home][title][]" type="text" placeholder="请输入操作名称">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">入口标识</span>
                                <input class="form-control" name="bindings[home][do][]" type="text" placeholder="请输入操作入口">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作附加数据</span>
                                <input class="form-control" name="bindings[home][data][]" type="text" placeholder="操作附加数据">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
                            <div style="margin-left:-15px;">
                                <label class="checkbox-inline" style="vertical-align:bottom">
                                    <input type="checkbox" name="bindings[home][directly][]" value="false">无需登陆直接展示
                                </label> &nbsp; &nbsp; &nbsp;
                                <a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle" title="删除此操作"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <div class="well well-sm">
                        <a href="javascript:;" onclick="addFeature('home', '微站首页导航图标');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
                    </div>
                    <span class="help-block">
                        在微站的首页上显示相关功能的链接入口(手机端操作), 一般用于通用功能的展示.
                    </span>
                    <span class="help-block"><strong>注意: 微站首页导航图标扩展功能定义于 WeSite 类的实现中</strong></span>
                </div>
            </div>
            <!--微站个人中心导航-->
            <div id="bindings-profile">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">微站个人中心导航</label>

                    <div class="col-sm-10">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作名称</span>
                                <input class="form-control" name="bindings[profile][title][]" type="text" placeholder="请输入操作名称">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">入口标识</span>
                                <input class="form-control" name="bindings[profile][do][]" type="text" placeholder="请输入操作入口">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作附加数据</span>
                                <input class="form-control" name="bindings[profile][data][]" type="text" placeholder="操作附加数据">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
                            <div style="margin-left:-15px;">
                                <label class="checkbox-inline" style="vertical-align:bottom">
                                    <input type="checkbox" name="bindings[profile][directly][]" value="false">无需登陆直接展示
                                </label> &nbsp; &nbsp; &nbsp;
                                <a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle" title="删除此操作"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <div class="well well-sm">
                        <a href="javascript:;" onclick="addFeature('profile', '微站个人中心导航');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
                    </div>
                    <span class="help-block">
                        在微站的个人中心上显示相关功能的链接入口(手机端操作), 一般用于个人信息, 或针对个人的数据的展示.
                    </span>
                    <span class="help-block"><strong>注意: 微站个人中心导航扩展功能定义于 WeSite 类的实现中</strong></span>
                </div>
            </div>
            <!--微站快捷功能导航-->
            <div id="bindings-quick">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">微站快捷功能导航</label>

                    <div class="col-sm-10">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作名称</span>
                                <input class="form-control" name="bindings[quick][title][]" type="text" placeholder="请输入操作名称">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">入口标识</span>
                                <input class="form-control" name="bindings[quick][do][]" type="text" placeholder="请输入操作入口">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">
                                <span class="input-group-addon">操作附加数据</span>
                                <input class="form-control" name="bindings[quick][data][]" type="text" placeholder="操作附加数据">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
                            <div style="margin-left:-15px;">
                                <label class="checkbox-inline" style="vertical-align:bottom">
                                    <input type="checkbox" name="bindings[quick][directly][]" value="false">无需登陆直接展示
                                </label> &nbsp; &nbsp; &nbsp;
                                <a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle" title="删除此操作"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <div class="well well-sm">
                        <a href="javascript:;" onclick="addFeature('quick', '微站快捷功能导航');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
                    </div>
                    <span class="help-block">
                        在微站的快捷菜单上展示相关功能的链接入口(手机端操作), 仅在支持快捷菜单的微站模块上有效.
                    </span>
                    <span class="help-block"><strong>注意: 微站快捷功能导航扩展功能定义于 WeSite 类的实现中</strong></span>
                </div>
            </div>
            <h5 class="page-header">模块发布
                <small>这里来定义模块发布时需要的配置项</small>
            </h5>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">兼容的版本</label>

                <div class="col-sm-10 col-xs-12">
                    <label class="checkbox-inline">
                        <input name="versionCode[]" type="checkbox" value="2.0">hdcms 2.0</label>
                    <span class="help-block">当前模块兼容的系统版本, 安装时会判断版本信息, 不兼容的版本将无法安装</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块缩略图</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="file" name="thumbnail" class="filestyle form-control" data-iconName="glyphicon glyphicon-folder-open" data-buttonText="上传图片"/>

                    <span class="help-block">用 48*48 的图片来让你的模块更吸引眼球吧</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块封面</label>

                <div class="col-sm-10 col-xs-12">
                    <input type="file" name="cover_plan" class="filestyle form-control" data-iconName="glyphicon glyphicon-folder-open" data-buttonText="上传图片"/>

                    <span class="help-block">模块封面, 大小为 600*350, 更好的设计将会获得官方推荐位置</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块安装脚本</label>

                <div class="col-sm-10 col-xs-12">
                    <textarea class="form-control" name="install" rows="4"></textarea>
                    <span class="help-block">当前模块全新安装时所执行的脚本, 可以定义为SQL语句. 也可以指定为单个的php脚本文件, 如: install.php</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块卸载脚本</label>

                <div class="col-sm-10 col-xs-12">
                    <textarea class="form-control" name="uninstall" rows="4"></textarea>
                    <span class="help-block">当前模块卸载时所执行的脚本, 可以定义为SQL语句. 也可以指定为单个的php脚本文件, 如: uninstall.php</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块升级脚本</label>

                <div class="col-sm-10 col-xs-12">
                    <textarea class="form-control" name="upgrade" rows="4"></textarea>
                    <span class="help-block">当前模块更新时所执行的脚本, 可以定义为SQL语句. 也可以指定为单个的php脚本文件, 如: upgrade.php. (推荐使用php脚本, 方便检测字段及兼容性)</span>
                    <input type="hidden" name="token" value="6708fa25">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <input name="method" type="hidden" value="download">
                    <input name="token" type="hidden" value="6708fa25">
                    <input type="submit" class="btn btn-primary" id="createBtn" name="submit" onclick="$(':hidden[name=method]').val('create');" value="直接生成模块模板">
                    <span class="help-block">点此直接在源码目录 addons/<span class="identifie"></span> 处生成模块开发的模板文件, 方便快速开发</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

                <div class="col-sm-10 col-xs-12">
                    <input type="submit" class="btn btn-primary span3" name="submit" onclick="$(':hidden[name=method]').val('download');" value="下载模块模板">
                    <span class="help-block">如果您的服务器不能直接读写文件, 请下载后上传至服务器目录 addons/<span class="identifie"></span> 下来编辑开发 </span>
                </div>
            </div>
        <input type='hidden' name='__TOKEN__' value='c7f0747b31eddb0c712d5e7ccbd79e03'/></form>
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
    function addFeature(type, title) {
        var html = '<div class="form-group">\
            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">' + title + '</label>\
            <div class="col-sm-10">\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">\
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">\
            <span class="input-group-addon">操作名称</span>\
            <input class="form-control" name="bindings[' + type + '][title][]" type="text" placeholder="请输入操作名称">\
            </div>\
            </div>\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">\
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">\
            <span class="input-group-addon">入口标识</span>\
            <input class="form-control" name="bindings[' + type + '][do][]" type="text" placeholder="请输入操作入口">\
            </div>\
            </div>\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">\
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">\
            <span class="input-group-addon">操作附加数据</span>\
            <input class="form-control" name="bindings[' + type + '][data][]" type="text" placeholder="操作附加数据">\
            </div>\
            </div>\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ' + (type == 'business' || type == 'member' ? 'hide' : "") + '">\
            <div style="margin-left:-15px;">\
            <label class="checkbox-inline" style="vertical-align:bottom">\
            <input type="checkbox" name="bindings[' + type + '][directly][]" value="false">无需登陆直接展示\
            </label> &nbsp; &nbsp; &nbsp;\
    <a href="javascript:;" onclick="$(this).parents(\'.form-group\').eq(0).remove()" class="fa fa-times-circle" title="删除此操作"></a>\
            </div>\
            </div>\
            </div>\
            </div>';
        $('#bindings-' + type).append(html);
    }


    $("form").submit(function () {
        //验证表单
        var msg = '';
        var title = $.trim($(':text[name="title"]').val());
        if (title == '') {
            msg += '模块名称不能为空 <br/>';
        }
        var names = $.trim($(':text[name="name"]').val());
        if (!/^[a-z]\w+$/i.test(names)) {
            msg += '模块标识必须以英文字母开始. 后跟英文,字母,数字或下划线<br/>';
        }
        var version = $.trim($(':text[name="version"]').val());
        if (!/^[\d\.]+$/i.test(version)) {
            msg += '请设置版本号. 版本号只能为数字或小数点<br/>';
        }
        var resume = $.trim($(':text[name="resume"]').val());
        if (resume == '') {
            msg += '模块简述不能为空<br/>';
        }
        var detail = $.trim($('[name="detail"]').val());
        if (detail == '') {
            msg += '请输入详细介绍内容<br/>';
        }
        var author = $.trim($(':text[name="author"]').val());
        if (author == '') {
            msg += '作者不能为空<br/>';
        }
        var url = $.trim($(':text[name="url"]').val());
        if (url == '') {
            msg += '请输入发布url<br/>';
        }
        var versionCode = $.trim($(':checkbox[name="versionCode[]"]:checked').val());
        if (versionCode == '') {
            msg += '请选择兼容版本<br/>';
        }
        if (msg) {
            require(['dialog'], function (dialog) {
                dialog.message({
                    message: msg,
                    icon: 'fa-times-circle',
                });
            });
            return false;
        }
    });
</script>