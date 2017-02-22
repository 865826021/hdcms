<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>HDCMS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="{{__ROOT__}}/node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{__ROOT__}}/node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="{{__ROOT__}}/node_modules/hdjs/js/jquery.min.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/app/util.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/require.js"></script>
	<script>
		hdjs = {
			'base': 'node_modules/hdjs',
			'uploader': '{{u("system/component/uploader")}}',
			'filesLists': '{{u("system/component/filesLists")}}',
			'removeImage': '{{u("system/component/removeImage")}}',
		};
	</script>
	<script src="{{__ROOT__}}/node_modules/hdjs/config.js"></script>
	<script src="{{__ROOT__}}/resource/js/hdcms.js"></script>
	<link href="{{__ROOT__}}/resource/css/hdcms.css" rel="stylesheet">
	<![endif]-->
	<script>
		if (navigator.appName == 'Microsoft Internet Explorer') {
			if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
				alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
			}
		}
	</script>
</head>
<body class="login">
<div class="container logo" style="width:700px;">
	<div style="background: url('resource/images/logo.png') no-repeat; background-size: contain;height: 60px;"></div>
</div>
<br/>
<div class="container well" style="width:700px;">
	<div class="row ">
		<div class="col-md-12">
			<form method="post" action="">
				<div class="form-group">
					<label class="star">用户名</label>
					<input type="text" name="username" class="form-control" placeholder="请输入用户名">
				</div>
				<div class="form-group">
					<label class="star">密码</label>
					<input type="password" name="password" class="form-control" placeholder="请输入不少于5位的密码">
				</div>
				<div class="form-group">
					<label class="star">确认密码</label>
					<input type="password" name="password2" class="form-control" placeholder="请再次输入不少于5位的密码">
				</div>
				<div class="form-group">
					<label class="star">邮箱</label>
					<input type="text" name="email" class="form-control" placeholder="请输入邮箱">
				</div>
				<div class="form-group">
					<label class="star">手机号</label>
					<input type="text" name="mobile" class="form-control" placeholder="请输入手机号">
				</div>
				<if value="v('config.register.enable_code')==1">
					<div class="form-group">
						<label style="display: block" class="star">验证码</label>
						<input type="text" name="code" class="form-control" placeholder="请输入验证码"
						       style="display: inline;width:65%">
						<img src="{{u('code')}}" onclick="this.src='{{u('code')}}&_Math.random()'" style="cursor: pointer">
					</div>
				</if>
				<button type="submit" class="btn btn-primary">注册</button>
				<a class="btn btn-default" href="{{u('system/entry/login',['from'=>$_GET['from']])}}">登录</a>
			</form>
		</div>
	</div>
	<div class="copyright">
		<br/>
		Powered by hdcms v2.0 © 2014-2019 www.hdcms.com
	</div>
</div>
<script>
	require(['util'],function(util){
		util.submit({'successUrl':"{{u('login')}}"});
	})
</script>
</body>
</html>