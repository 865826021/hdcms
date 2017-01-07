<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>HDCMS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<link href="resource/css/hdcms.css" rel="stylesheet">
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
<body class="login">
<div class="container logo" style="width:700px;">
	<div style="background: url('resource/images/logo.png') no-repeat; background-size: contain;height: 60px;"></div>
</div>
<br/>
<div class="container well" style="width:700px;">
	<if value="$errors">
		<div class="alert alert-warning">
			<foreach from="$errors" value="$v">
				<p>{{$v}}</p>
			</foreach>
		</div>
	</if>
	<div class="row ">
		<div class="col-md-12">
			<form method="post" action="">
				{{csrf_field()}}
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
					<label class="star">QQ号</label>
					<input type="text" name="qq" class="form-control" placeholder="请输入QQ号码">
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
<br/><br/><br/><br/>
</body>
</html>