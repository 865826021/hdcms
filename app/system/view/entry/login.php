<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>HDCMS开源免费-微信/桌面/移动三网通CMS系统</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="node_modules/hdjs/js/jquery.min.js"></script>
	<script src="node_modules/hdjs/app/util.js"></script>
	<script src="node_modules/hdjs/require.js"></script>
	<script src="node_modules/hdjs/app/config.js"></script>
	<link href="resource/css/hdcms.css" rel="stylesheet">
	<script>
		if (navigator.appName == 'Microsoft Internet Explorer') {
			if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
				alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
			}
		}
	</script>
</head>
<body class="login">
<div class="container logo">
	<div style="background: url('resource/images/logo.png') no-repeat; background-size: contain;height: 60px;"></div>
</div>
<form action="" method="post" enctype="multipart/form-data">
	{{csrf_field()}}
	<input type="file" name="a">
	<input type="submit">
</form>
<div class="container well">
	<if value="$errors">
		<div class="alert alert-warning">
			<foreach from="$errors" value="$v">
				<p>{{$v}}</p>
			</foreach>
		</div>
	</if>
	<div class="row ">
		<div class="col-md-6">

			<form method="post" action="">
				{{csrf_field()}}
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
						       class="form-control input-lg" placeholder="请输入密码">
					</div>
				</div>
				<if value="v('config.site.enable_code')==1">
					<div class="form-group">
						<label>验证码</label>

						<div class="input-group">
							<input type="text" class="form-control input-lg" name="code" placeholder="请输入验证码"
							       aria-describedby="basic-addon2">
							<span class="input-group-addon">
                            <img src="{{u('code')}}" onclick="this.src='{{u('code')}}&_Math.random()'"
                                 style="cursor: pointer">
                        </span>
						</div>
					</div>
				</if>
				<button type="submit" class="btn btn-primary btn-lg">登录</button>
				<a class="btn btn-default btn-lg" href="{{u('system/entry/register',['from'=>$_GET['from']])}}">注册</a>
			</form>
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