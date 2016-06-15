<?php if (!defined('APP_PATH')) exit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS - 开源免费内容管理系统 - Powered by HDCMS.COM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="resource/hdjs/css/bootstrap.min.css" rel="stylesheet">
    <link href="resource/hdjs/css/font-awesome.min.css" rel="stylesheet">
    <script src="resource/hdjs/js/jquery.min.js"></script>
    <script src="resource/hdjs/app/util.js"></script>
    <script src="resource/hdjs/require.js"></script>
    <script src="resource/hdjs/app/config.js"></script>
    <link rel="stylesheet" href="theme/default/mobile/ucenter/css/mobile.css">
    <script src="theme/default/mobile/ucenter/js/mobile.js"></script>
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        window.sys = {
            attachment: 'attachment',
            uid: "{{$_SESSION['user']['uid']}}",
            siteid: "{{Session::get('setid')}}",
            root: "{{__ROOT__}}"
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
<body>
<div class="head-bg-gray"></div>
<blade name="content"/>
</body>
</html>