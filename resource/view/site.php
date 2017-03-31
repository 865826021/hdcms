<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{{v('site.info.name')}} - HDCMS开源免费内容管理系统</title>
    <include file="resource/view/common.php"/>
    <include file="resource/view/hdjs.php"/>
</head>
<body class="site">
<?php $LINKS = \Menu::get(); ?>
<div class="container-fluid admin-top">
    <!--导航-->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <if value="q('session.system.login')=='hdcms'">
                        <li>
                            <a href="?s=system/site/lists">
                                <i class="fa fa-reply-all"></i> 返回系统
                            </a>
                        </li>
                    </if>
                    <foreach from="$LINKS['menus']" value="$m">
                        <if value="$m['mark']==Request::get('mark')">
                            <li class="top_menu active">
                                <else/>
                            <li class="top_menu">
                        </if>
                        <a href="{{$m['url']}}&siteid={{SITEID}}&mark={{$m['mark']}}">
                            <i class="'fa-w {{$m['icon']}}"></i> {{$m['title']}}
                        </a>
                        </li>
                    </foreach>
                </ul>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <if value="q('session.system.login')=='hdcms'">
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                               style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "
                               aria-expanded="false">
                                <i class="fa fa-group"></i> {{v('site.info.name')}} <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="?s=system/site/edit&siteid={{SITEID}}"><i class="fa fa-weixin fa-fw"></i>
                                        编辑当前账号资料
                                    </a>
                                </li>
                                <li><a href="?s=system/site/lists"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
                            </ul>
                        </li>
                    </if>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="fa fa-w fa-user"></i>
                            {{v('user.info.username')}}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="?s=system/user/myPassword">我的帐号</a></li>
                            <if value="q('session.system.login')=='hdcms'">
                                <li><a href="?s=system/manage/menu">系统选项</a></li>
                            </if>
                            <li role="separator" class="divider"></li>
                            <li><a href="?s=system/entry/quit">退出</a></li>
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
            <div class="search-menu">
                <input class="form-control input-lg" type="text" placeholder="输入菜单名称可快速查找"
                       onkeyup="search(this)">
            </div>
            <!--扩展模块动作 start-->
            <if value="'package'==Request::get('mark') && Request::get('m')">
                <div class="btn-group module_action_type">
                    <a class="btn {{Request::get('mt')=='default'?'btn-primary':'btn-default'}} default"
                       href="{{url_del('mt')}}&mt=default">
                        默认
                    </a>
                    <a class="btn {{Request::get('mt')=='system'?'btn-primary':'btn-default'}} system"
                       href="{{url_del('mt')}}&mt=system">
                        系统
                    </a>
                    <a class="btn {{Request::get('mt')=='group'?'btn-primary':'btn-default'}} group"
                       href="{{url_del('mt')}}&mt=group">
                        组合
                    </a>
                </div>
            </if>
            <div class="panel panel-default">
                <!--系统菜单-->
                <if value="!in_array(Request::get('mt'),['default'])">
                    <foreach from="$LINKS['menus']" value="$m">
                        <if value="$m['mark']==Request::get('mark')">
                            <foreach from="$m['_data']" value="$d">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{$d['title']}}</h4>
                                    <a class="panel-collapse" data-toggle="collapse" href="javascript:;">
                                        <i class="fa fa-chevron-circle-down"></i>
                                    </a>
                                </div>
                                <ul class="list-group menus">
                                    <foreach from="$d['_data']" value="$g">
                                        <li class="list-group-item" id="{{$g['id']}}">
                                            <if value="$g['append_url']">
                                                <a class="pull-right append_url"
                                                   href="{{$g['append_url']}}&siteid={{SITEID}}&mark={{$g['mark']}}&mi={{$g['id']}}">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </if>
                                            <a href="{{$g['url']}}&siteid={{SITEID}}&mark={{$g['mark']}}&mi={{$g['id']}}&mt={{Request::get('mt')}}">
                                                {{$g['title']}}
                                            </a>
                                        </li>
                                    </foreach>
                                </ul>
                            </foreach>
                        </if>
                    </foreach>
                </if>
                <!----------返回模块列表 start------------>
                <if value="$LINKS['module'] && Request::get('mark')=='package' && in_array(Request::get('mt'),['default'])">
                    <div class="panel-heading">
                        <h4 class="panel-title">系统功能</h4>
                        <a class="panel-collapse" data-toggle="collapse" aria-expanded="true">
                            <i class="fa fa-chevron-circle-down"></i>
                        </a>
                    </div>
                    <ul class="list-group" aria-expanded="true" mark="package">
                        <li class="list-group-item">
                            <a href="{{site_url('site.entry.home')}}">
                                <i class="fa fa-reply-all"></i> 返回模块列表
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{site_url('site.entry.module')}}&m={{$_GET['m']}}">
                                <i class="fa fa-desktop"></i> {{$LINKS['module']['module']['title']}}
                            </a>
                        </li>
                    </ul>
                </if>
                <if value="Request::get('mark')=='package' && in_array(Request::get('mt'),['group','default'])">
                    <foreach from="$LINKS['module']['access']" key="$title" value="$t">
                        <div class="panel-heading module_back module_action">
                            <h4 class="panel-title">{{$title}}</h4>
                            <a class="panel-collapse" data-toggle="collapse" aria-expanded="true">
                                <i class="fa fa-chevron-circle-down"></i>
                            </a>
                        </div>
                        <ul class="list-group " aria-expanded="true">
                            <foreach from="$t" value="$m">
                                <li class="list-group-item" id="{{$m['_hash']}}">
                                    <a href="{{$m['url']}}&siteid={{SITEID}}&mi={{$m['_hash']}}&mt={{Request::get('mt')}}">
                                        <i class="{{$m['ico']}}"></i> {{$m['title']}}
                                    </a>
                                </li>
                            </foreach>
                        </ul>
                    </foreach>
                </if>
                <!--模块列表-->
                <if value="Request::get('mark')=='package' && in_array(Request::get('mt'),['group','system',''])">
                    <foreach from="$LINKS['moduleLists']" key="$t" value="$d">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{$t}}</h4>
                            <a class="panel-collapse">
                                <i class="fa fa-chevron-circle-down"></i>
                            </a>
                        </div>
                        <ul class="list-group menus">
                            <foreach from="$d" value="$g">
                                <li class="list-group-item">
                                    <a href="{{site_url('site.entry.module')}}&m={{$g['name']}}&mt=default">
                                        {{$g['title']}}
                                    </a>
                                </li>
                            </foreach>
                        </ul>
                    </foreach>
                </if>
                <!--模块列表 end-->
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <!--有模块管理时显示的面包屑导航-->
            <if value="v('module.title') && v('module.is_system')==0">
                <ol class="breadcrumb" style="background-color: #f9f9f9;padding:8px 0;margin-bottom:10px;">
                    <li>
                        <a href="?s=site/entry/home&mark=package">
                            &nbsp;&nbsp;<i class="fa fa-cogs"></i> 扩展模块
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{site_url('site.entry.module')}}&m={{v('module.name')}}">{{v('module.title')}}</a>
                    </li>
                    <if value="$module_action_name">
                        <li class="active">
                            {{$module_action_name}}
                        </li>
                    </if>
                </ol>
            </if>
            <blade name="content"/>
        </div>
    </div>
</div>
<div class="master-footer">
    <a href="http://www.houdunwang.com">猎人训练</a>
    <a href="http://www.hdphp.com">开源框架</a>
    <a href="http://bbs.houdunwang.com">后盾论坛</a>
    <br>
    Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
</div>
<script src="{{__ROOT__}}/resource/js/menu.js"></script>
<script src="{{__ROOT__}}/resource/js/quick_navigate.js"></script>
<script>
    require(['bootstrap']);
</script>
<!--右键菜单添加到快捷导航-->
<div id="context-menu">
    <ul class="dropdown-menu" role="menu">
        <li><a tabindex="-1" href="#">添加到快捷菜单</a></li>
    </ul>
</div>
<!--右键菜单删除快捷导航-->
<div id="context-menu-del">
    <ul class="dropdown-menu" role="menu">
        <li><a tabindex="-1" href="#">删除菜单</a></li>
    </ul>
</div>
<!--底部快捷菜单导航-->
<?php $QUICKMENU = \Menu::getQuickMenu(); ?>
<if value="$QUICKMENU['status']">
    <div class="quick_navigate">
        <div class="btn-group">
		<span class="btn btn-success btn-sm">
			快捷导航
		</span>
            <foreach from="$QUICKMENU['system']" value="$v">
                <a class="btn btn-default btn-sm" href="{{$v['url']}}">
                    {{$v['title']}}
                </a>
            </foreach>
            <foreach from="$QUICKMENU['module']" value="$v">
                <div class="btn-group dropup">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{$v['title']}} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <foreach from="$v['action']" value="$a">
                            <li><a href="{{$a['url']}}">{{$a['title']}}</a></li>
                        </foreach>
                    </ul>
                </div>
            </foreach>
        </div>
        <i class="fa fa-times-circle-o pull-right fa-2x close_quick_menu"></i>
    </div>
</if>
</body>
</html>