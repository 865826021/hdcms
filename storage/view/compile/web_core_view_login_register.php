<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>HDCMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script>
        var hdjs = {
            path: 'resource/hdjs',
            upimage: {
                upload: "<?php echo u('core/upload/upimage')?>",
                list: 'lists.php'
            }
        }
    </script>
    <link rel="stylesheet" href="../../../web/common/css/common.css">
    <script src="resource/hdjs/dist/hd.js"></script>
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
<div class="container logo" style="width:700px;">
    <div style="background: url('../../../web/common/images/logo.png') no-repeat; background-size: contain;height: 60px;"></div>
</div>
<br/>
<div class="container well" style="width:700px;">
    <div class="row ">
        <div class="col-md-12">
            <form method="post" action="">
                <div class="form-group">
                    <label class="star">用户名</label>
                    <input type="text" name="username"  class="form-control" placeholder="请输入用户名">
                </div>
                <div class="form-group">
                    <label class="star">密码</label>
                    <input type="password" name="password"  class="form-control" placeholder="请输入不少于6位的密码">
                </div>
                <div class="form-group">
                    <label class="star">确认密码</label>
                    <input type="password" name="password2"  class="form-control" placeholder="请再次输入不少于6位的密码">
                </div>
                <?php foreach ((array)$field as $f){?>
                <div class="form-group">
                    <?php if($f['required']){?>
                
                        <label class="star"><?php echo $f['title']?></label>
                        <?php }else{?>
                    <label><?php echo $f['title']?></label>
                    
               <?php }?>
                    <input type="text" name="<?php echo $f['field']?>"  class="form-control">
                </div>
                <?php }?>

                <div class="form-group">
                    <label style="display: block" class="star">验证码</label>
                    <input type="text" name="code"  class="form-control" placeholder="请输入验证码" style="display: inline;width:65%">
                    <img src="<?php echo u('code')?>" onclick="this.src='<?php echo u('code')?>&_Math.random()'" style="cursor: pointer">
                </div>
                <button type="submit" class="btn btn-primary">注册</button>
                <a class="btn btn-default" href="<?php echo u('login')?>">登录</a>
            <input type='hidden' name='__TOKEN__' value='f27a074e258188c5be29446981309821'/></form>
        </div>
    </div>
    <div class="copyright">
        <br/>
        Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
    </div>
</div>
<br/><br/><br/><br/>
</body>
</html>