<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>温馨提示</title>
    <script src="http://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
    <script src="resource/hdjs/dist/hdjs.js"></script>
</head>
<?php if(isset($_GET['isajax'])){?>
                
    <body>
    <script>
        parent.require(['bootstrap', 'util'], function (bootstrap, util) {
            var modalobj = util.message('<?php echo $message?>', '', 'error');
        })
    </script>
    </body>
    <?php }else{?>
    <body style="background: url('../../../web/common/images/bg3.jpg');background-size: cover">
    <script>
        var timeout = '<?php echo $timeout?>';
        var url = '<?php echo $url?>';
        var jsUrl = '<?php echo $jsUrl?>';
        var message = '<?php echo $message?>';
    </script>
    <!--导航-->
    <nav class="navbar navbar-inverse" style="border-radius: 0px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="http://bbs.houdunwang.com" target="_blank"><i class="fa fa-w fa-forumbee"></i> 后盾论坛</a>
                    </li>
                    <li>
                        <a href="http://www.houdunwang.com" target="_blank"><i class="fa fa-w fa-phone-square"></i> 联系后盾</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--导航end-->

    <div class="container-fluid">

        <h1>&nbsp;</h1>

        <div style="background: url('../../../web/common/images/logo.png') no-repeat;background-size: contain;height:60px;"></div>
        <br/>

        <div class="alert alert-info clearfix jumbotron">
            <h4>&nbsp;</h4>

            <div class="col-xs-3">
                <i class="fa fa-times-circle fa-5x fa-w"></i>
            </div>
            <div class="col-xs-9">
                <p><?php echo $message?></p>

                <p><a href="javascript:;" onclick="<?php echo $jsUrl?>" class="alert-link">如果你的浏览器没有跳转, 请点击此链接</a></p>
            </div>
        </div>
    </div>
    <script>
        if (timeout) {
            setTimeout(function () {
                if (url) {
                    location.href = url;
                } else {
                    window.history.go(-1);
                }

            }, timeout)
        }
    </script>
    </body>

               <?php }?>
</html>