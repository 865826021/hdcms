<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><blade name="title"/></title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="resource/hdjs/css/bootstrap.min.css" rel="stylesheet">
    <link href="resource/hdjs/css/font-awesome.min.css" rel="stylesheet">
    <script src="resource/hdjs/js/jquery.min.js"></script>
    <script src="resource/hdjs/app/util.js"></script>
    <script src="resource/hdjs/require.js"></script>
    <script src="resource/hdjs/app/config.js"></script>
    <script src="resource/js/common.js"></script>
    <script src="ucenter/js/mobile.js"></script>
    <script src="ucenter/js/quickmenu.js"></script>
    <link rel="stylesheet" href="ucenter/css/mobile.css">
</head>
<body>
<blade name="content"/>
<!--会员头部固定条-->
<widget name="header">
    <div class="container">
        <nav class="navbar navbar-default navbar-fixed-top uc-header">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{web_url('entry/home')}}" style="position: absolute;">
                    <i class="fa fa-chevron-left"></i>
                </a>
                <p class="navbar-text navbar-right text-center">{{title}}</p>
            </div>
        </nav>
    </div>
    <div style="height: 55px"></div>
</widget>
<!--会员头部固定条 end-->
<!--会员卡底部-->
<widget name="ticket_footer">
    <div style="height:60px;"></div>
    <nav class="navbar navbar-default navbar-fixed-bottom card_footer">
        <a href="{{web_url('ticket/convert')}}&type={{$_GET['type']}}">
            <i class="fa fa-credit-card" aria-hidden="true"></i> 兑换
        </a>
        <a href="{{web_url('entry/home')}}">
            <i class="fa fa-user"></i> 会员中心
        </a>
    </nav>
</widget>
<!--会员卡底部 end-->
</body>
</html>