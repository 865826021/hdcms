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
	<link href="resource/css/common.css" rel="stylesheet">
	<link rel="stylesheet" href="{{__VIEW__}}/entry/css/css.css">
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
	<div style="background: url('resource/images/logo.png') no-repeat; background-size: contain;height: 60px;"></div>
</div>
<br/>
<div class="container well" style="width:700px;">
	<div class="row ">
		<div class="col-md-12">
			<form method="post" action="">
				<div class="form-group">
					<label class="star">用户名</label>
					<input type="text" name="username" class="form-control" placeholder="请输入用户名" required="required">
				</div>
				<div class="form-group">
					<label class="star">密码</label>
					<input type="password" name="password" class="form-control" placeholder="请输入不少于6位的密码" required="required">
				</div>
				<div class="form-group">
					<label class="star">确认密码</label>
					<input type="password" name="password2" class="form-control" placeholder="请再次输入不少于6位的密码" required="required">
				</div>
				<div class="form-group">
					<label class="star">邮箱</label>
					<input type="text" name="email" class="form-control" placeholder="请输入邮箱" required="required">
				</div>
				<div class="form-group">
					<label class="star">QQ号</label>
					<input type="text" name="qq" class="form-control" placeholder="请输入QQ号码" required="required">
				</div>
				<div class="form-group">
					<label class="star">手机号</label>
					<input type="text" name="mobile" class="form-control" placeholder="请输入手机号" required="required">
				</div>
				<if value="$registerConfig['enable_code']==1">
					<div class="form-group">
						<label style="display: block" class="star">验证码</label>
						<input type="text" name="code" class="form-control" placeholder="请输入验证码" required="required"
						       style="display: inline;width:65%">
						<img src="{{u('code')}}" onclick="this.src='{{u('code')}}&_Math.random()'" style="cursor: pointer">
					</div>
				</if>
				<button type="submit" class="btn btn-primary">注册</button>
				<a class="btn btn-default" href="?s=system/entry/login">登录</a>
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