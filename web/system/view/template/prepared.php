<extend file="resource/view/system"/>
<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">已经安装模板</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="{{u('installed')}}">已经安装模板</a></li>
        <li role="presentation" class="active"><a href="?s=system/template/prepared">安装模板</a></li>
        <li role="presentation"><a href="?s=system/template/design">设计新模板</a></li>
        <li role="presentation"><a href="?s=system/store/module">应用商城</a></li>
    </ul>
    <h5 class="page-header">未安装的本地模板</h5>
    <foreach from="$locality" value="$local">
        <div class="media">
            <div class="pull-right">
                <div style="margin-right: 10px;">
                    <a href="{{u('install',array('name'=>$local['name']))}}">安装模板</a>
                </div>
            </div>
            <div class="media-left">
                <a href="#">
                    <img class="media-object" src="{{$local['thumb']}}" style="width: 50px;height: 50px;">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading">{{$local['title']}}
                    <small>（标识：{{$local['name']}} 版本：{{$local['version']}} 作者：{{$local['author']}}）</small>
                </h4>
                <a href="javascript:;" class="detail">详细介绍</a>
            </div>
            <div class="alert alert-info" role="alert"><strong>功能介绍</strong>： {{$local['description']}}</div>
        </div>
    </foreach>
    <style>
        .media {
            border-bottom: solid 1px #dcdcdc;
            padding-bottom: 6px;
        }

        .media h4.media-heading {
            font-size: 16px;
        }

        .media .media-left a img {
            border-radius: 5px;
        }

        .media h4.media-heading small {
            font-size: 65%;
        }

        .media .media-body a {
            display: inline-block;
            font-size: 14px;
            padding-top: 6px;
        }

        .media .alert {
            margin-top: 6px;
            display: none;
        }
    </style>
    <script>
        $('.detail').click(function () {
            //隐藏所有详细介绍内容
            $('.detail').not(this).parent().next().hide();
            //显示介绍
            $(this).parent().next().toggle();
        });
    </script>
</block>
