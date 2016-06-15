<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="resource/hdjs/css/bootstrap.min.css" rel="stylesheet">
    <link href="resource/hdjs/css/font-awesome.min.css" rel="stylesheet">
    <script src="resource/hdjs/js/jquery.min.js"></script>
    <script src="resource/hdjs/app/util.js"></script>
    <script src="resource/hdjs/require.js"></script>
    <script src="resource/hdjs/app/config.js"></script>
    <script src="web/resource/js/common.js"></script>
    <link href="web/resource/css/common.css" rel="stylesheet">
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
<body class="admin-login">
<div class="container logo">
    <div style="background: url('web/resource/images/logo.png') no-repeat; background-size: contain;height: 60px;"></div>
</div>
<div class="container well">
    <div class="row ">
        <div class="col-md-6">
            <form method="post" action="">
                <div class="form-group">
                    <label>帐号</label>

                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-w fa-user"></i></div>
                        <input type="text" name="username" ng-model="formData.username" class="form-control input-lg"
                               placeholder="请输入帐号">
                    </div>
                </div>
                <div class="form-group">
                    <label>密码</label>

                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-w fa-key"></i></div>
                        <input type="password" name="password" ng-model="formData.password"
                               class="form-control input-lg"
                               placeholder="请输入密码">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">登录</button>
                <a class="btn btn-default btn-lg" href="<?php echo u('register')?>">注册</a>
            <input type='hidden' name='__TOKEN__' value='668a1e78d07966e2bc18a4cf4e16f044'/></form>
        </div>
        <div class="col-md-6">
            <div style="background: url('resource/images/houdunwang.jpg');background-size:100% ;height:230px;"></div>
        </div>
    </div>
    <div class="copyright">
        Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
    </div>
</div>
</body>
</html>