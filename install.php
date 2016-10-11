<?php 
$finish=<<<str
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>安装完成</title>
	<link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
	<link href="http://cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<script src="http://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body style="background: #33b2e2">
<div style="width:1100px;margin: 10px auto;">
	<div style="background: url(http://dev.hdcms.com/resource/images/logo.png) no-repeat;background-size:contain;height:80px;"></div>
	<br/>
	<div class="panel panel-default">
		<nav class="navbar navbar-default alert-info" style="border-radius: 0px;">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><strong>HDCMS 百分百开源免费,可用于任意商业项目!</strong>
						<small class="text-info">使用高效的HDPHP框架构建</small>
					</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="http://www.houdunwang.com" target="_blank">培训</a></li>
						<li><a href="http://www.hdcms.com" target="_blank">官网</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="panel-body">
			<div class="col-xs-3">
				<div class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-danger active" style="width: 100%;">
						100%
					</div>
				</div>
				<ul class="list-group">
					<li class="list-group-item"><strong>安装步骤</strong></li>
					<li class="list-group-item"><i class="fa fa-copyright"></i> 安装协议</li>
					<li class="list-group-item"><i class="fa fa-pencil-square-o"></i> 环境检测</li>
					<li class="list-group-item"><i class="fa fa-database"></i> 数据库配置</li>
					<li class="list-group-item"><i class="fa fa-cloud-download"></i> 下载软件包</li>
					<li class="list-group-item" style="background: #dff0d8"><i class="fa fa-check-circle"></i> 完成</li>
				</ul>
			</div>
			<div class="col-xs-9">
				<div class="alert alert-danger text-center">
					<h1><i class="fa fa-flag"></i> 恭喜你 HDCMS 已经安装成功</h1>
				</div>
				<a href="admin.php" class="btn btn-success btn-block btn-lg">登录系统</a>
			</div>
		</div>
	</div>
	<div class="text-center" style="color:#fff;"> ©2010 - 2019 hdcms.com Inc.</div>
</div>
</body>
</html>
str;
$download=<<<str
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>下载数据包</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <link href="http://cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body style="background: #33b2e2">
<div style="width:1100px;margin: 10px auto;">
    <div style="background: url(http://dev.hdcms.com/resource/images/logo.png) no-repeat;background-size:contain;height:80px;"></div>
    <br/>
    <div class="panel panel-default">
        <nav class="navbar navbar-default alert-info" style="border-radius: 0px;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><strong>HDCMS 百分百开源免费,可用于任意商业项目!</strong>
                        <small class="text-info">使用高效的HDPHP框架构建</small>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="http://www.houdunwang.com" target="_blank">培训</a></li>
                        <li><a href="http://www.hdcms.com" target="_blank">官网</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="panel-body">
            <div class="col-xs-3">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-danger active" style="width: 80%;">
                        80%
                    </div>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><strong>安装步骤</strong></li>
                    <li class="list-group-item"><i class="fa fa-copyright"></i> 安装协议</li>
                    <li class="list-group-item"><i class="fa fa-pencil-square-o"></i> 环境检测</li>
                    <li class="list-group-item"><i class="fa fa-database"></i> 数据库配置</li>
                    <li class="list-group-item" style="background: #dff0d8"><i class="fa fa-cloud-download"></i> 下载软件包</li>
                    <li class="list-group-item"><i class="fa fa-check-circle"></i> 完成</li>
                </ul>
            </div>
            <form class="form-horizontal" method="post">
                <div class="col-xs-9">
                    <div class="alert alert-info">
                        服务器下载人数过多或你的网速过低都会影响下载速度,也可能造成下载失败的情况,如果下载失败,你可以尝试 <a href="http://www.hdcms.com">下载离线数据包</a>.
                    </div>
                    <h1>
                        <div class="progress">
                            <div id="progress" class="progress-bar progress-bar-striped progress-bar-success active" style="width: 0%;">
                                0%
                            </div>
                        </div>
                    </h1>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center" style="color:#ffffff;"> © 2010 - 2019 hdcms.com Inc.</div>
</div>
</body>
</html>

<script>
    $(function () {
        //进度条
        var w = 0;
        var tid = setInterval(function () {
            w += 1;
            $("#progress").css({width: w + '%'})
            $("#progress").text(w + '%');
            if (w >= 100) {
                error();
            }
        }, 800);
        //下载包
        $.get('?a=downloadFile', function (status) {
            if (status == 1) {
                $("#progress").css({width: '100%'})
                $("#progress").text('100%');
                clearInterval(tid);
                location.href = '?a=table';
            } else {
                error();
            }
        });
    })
    function error() {
        alert('当前下载量太大,暂时无法提供下载,您可以去官网下载离线安装包');
        window.history.back();
    }
</script>
str;
$database=<<<str
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HDCMS 数据库配置</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <link href="http://cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body style="background: #33b2e2">
<div style="width:1100px;margin: 10px auto;">
    <div style="background: url(http://dev.hdcms.com/resource/images/logo.png) no-repeat;background-size:contain;height:80px;"></div>
    <br/>
    <div class="panel panel-default">
        <nav class="navbar navbar-default alert-info" style="border-radius: 0px;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><strong>HDCMS 百分百开源免费,可用于任意商业项目!</strong>
                        <small class="text-info">使用高效的HDPHP框架构建</small>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="http://www.houdunwang.com" target="_blank">培训</a></li>
                        <li><a href="http://www.hdcms.com" target="_blank">官网</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="panel-body">
            <div class="col-xs-3">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-danger active" style="width: 60%;">
                        60%
                    </div>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><strong>安装步骤</strong></li>
                    <li class="list-group-item"><i class="fa fa-copyright"></i> 安装协议</li>
                    <li class="list-group-item"><i class="fa fa-pencil-square-o"></i> 环境检测</li>
                    <li class="list-group-item" style="background: #dff0d8"><i class="fa fa-database"></i> 数据库配置</li>
                    <li class="list-group-item"><i class="fa fa-cloud-download"></i> 下载软件包</li>
                    <li class="list-group-item"><i class="fa fa-check-circle"></i> 完成</li>
                </ul>
            </div>
            <form class="form-horizontal" method="post">
                <div class="col-xs-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            数据库配置
                        </div>
                        <div class="panel-body" style="padding: 10px;">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">主机</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="host" value="127.0.0.1" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">数据库</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="database" required="required">
                                    <span class="help-block">数据库不存在时系统将尝试创建,但帐号没有权限时创建将失败</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户名</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="user" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">表前缀</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="prefix" value="hd_" required="required" placeholder="表前缀只能为字母或下划线">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            系统帐号
                        </div>
                        <div class="panel-body" style="padding: 10px;">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">帐号</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="username" value="admin" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="upassword" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">确认密码</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="upassword2" required="required">
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="javascript:history.back()" class="btn btn-success">返回</a>
                    <a href="javascript:;" class="btn btn-primary" onclick="post()">继续</a>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center" style="color:#ffffff;"> ©2010 - 2019 hdcms.com Inc.</div>
</div>
</body>
</html>
<script>
    function post() {
        var msg = '';
        if (!/^[a-z_]+$/i.test($.trim($("[name='prefix']").val()))) {
            msg = '表前缀只能为字母或下划线';
        }
        if ($.trim($("[name='upassword']").val()) != $.trim($("[name='upassword2']").val())) {
            msg = '系统管理员的两次密码不一致';
        }
        $("[name]").each(function () {
            if ($(this).attr('name') != 'password' && $.trim($(this).val()) == '') {
                msg = $(this).parent().prev().text() + "不能为空";
            }
        });

        if (msg) {
            alert(msg);
            return false;
        }
        $.post('?a=database', $('form').serialize(), function (res) {
            if (res.valid == 1) {
                location.href = '?a=download';
            } else {
                alert(res.message);
            }
        }, 'json');
    }
</script>
str;
$environment=<<<str
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>安装环境检测</title>
	<link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
	<link href="http://cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<script src="http://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body style="background: #33b2e2">
<div style="width:1100px;margin: 10px auto;">
	<div style="background: url(http://dev.hdcms.com/resource/images/logo.png) no-repeat;background-size:contain;height:80px;"></div>
	<br/>
	<div class="panel panel-default">
		<nav class="navbar navbar-default alert-info" style="border-radius: 0px;">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><strong>HDCMS 百分百开源免费,可用于任意商业项目!</strong>
						<small class="text-info">使用高效的HDPHP框架构建</small>
					</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="http://www.houdunwang.com" target="_blank">培训</a></li>
						<li><a href="http://www.hdcms.com" target="_blank">官网</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="panel-body">
			<div class="col-xs-3">
				<div class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-danger active" style="width: 40%;">
						40%
					</div>
				</div>
				<ul class="list-group">
					<li class="list-group-item"><strong>安装步骤</strong></li>
					<li class="list-group-item"><i class="fa fa-copyright"></i> 安装协议</li>
					<li class="list-group-item" style="background: #dff0d8"><i class="fa fa-pencil-square-o"></i> 环境检测</li>
					<li class="list-group-item"><i class="fa fa-database"></i> 数据库配置</li>
					<li class="list-group-item"><i class="fa fa-cloud-download"></i> 下载软件包</li>
					<li class="list-group-item"><i class="fa fa-check-circle"></i> 完成</li>
				</ul>
			</div>
			<div class="col-xs-9">
				<div class="panel panel-default">
					<div class="panel-heading">
						服务器信息
					</div>
					<div class="panel-body" style="padding: 10px;">
						<table class="table table-hover table-striped">
							<thead>
							<tr>
								<th>参数</th>
								<th>值</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>操作系统</td>
								<td>{hd:PHP_OS}</td>
							</tr>
							<tr>
								<td>服务器环境</td>
								<td>{hd:SERVER_SOFTWARE}</td>
							</tr>
							<tr>
								<td>PHP版本</td>
								<td>
									{hd:PHP_VERSION}
								</td>
							</tr>
							<tr>
								<td>上传限制</td>
								<td>
									{hd:upload_max_filesize}
								</td>
							</tr>
							<tr>
								<td>最大执行时间</td>
								<td>
									{hd:max_execution_time}
								</td>
							</tr>
							<tr>
								<td>脚本运行最大内存</td>
								<td>
									{hd:memory_limit}
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="alert alert-info">
					必须满足以下环境要求才可以运行HDCMS产品.
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						HDCMS 环境要求
					</div>
					<div class="panel-body" style="padding: 10px;">
						<table class="table table-hover table-striped">
							<thead>
							<tr>
								<th>参数</th>
								<th>说明</th>
								<th>状态</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>PHP版本</td>
								<td>要求PHP版本大于5.4</td>
								<td>{hd:h_PHP_VERSION}</td>
							</tr>
							<tr>
								<td>Mysql</td>
								<td>必须支持Mysql数据库</td>
								<td>{hd:h_mysql}</td>
							</tr>
							<tr>
								<td>Pdo</td>
								<td>不支持将不能操作数据库</td>
								<td>{hd:h_Pdo}</td>
							</tr>
							<tr>
								<td>GD图像库</td>
								<td>不支持将无法处理图像</td>
								<td>{hd:h_Gd}</td>
							</tr>
							<tr>
								<td>CURL</td>
								<td>微信等远程接口将需要CURL模块</td>
								<td>{hd:h_curl}</td>
							</tr>
							<tr>
								<td>openSSL</td>
								<td>需要支持</td>
								<td>{hd:h_openSSL}</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="alert alert-info">
					检测安装HDCMS目录的可写权限
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						目录权限检测
					</div>
					<div class="panel-body" style="padding: 10px;">
						<table class="table table-hover table-striped">
							<thead>
							<tr>
								<th>目录</th>
								<th>状态</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>./</td>
								<td>{hd:d_root}</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<a href="javascript:history.back()" class="btn btn-success">返回</a>
				<a href="javascript:;" onclick="database();" class="btn btn-primary">继续</a>
			</div>
		</div>
	</div>
	<div class="text-center" style="color:#fff;"> ©2010 - 2019 hdcms.com Inc.</div>
</div>
</body>
</html>

<script>
	function database() {
		if ($(".fa-times-circle").length > 0) {
			alert('当前环境不可以安装HDCMS');
		} else {
			location.href = '?a=database';
		}
	}
</script>
str;
$copyright=<<<str
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HDCMS 版权声明</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <link href="http://cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body style="background: #33b2e2">
<div style="width:1100px;margin: 10px auto;">
    <div style="background: url(http://dev.hdcms.com/resource/images/logo.png) no-repeat;background-size:contain;height:80px;"></div>
    <br/>
    <div class="panel panel-default">
        <nav class="navbar navbar-default alert-info" style="border-radius: 0px;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><strong>HDCMS 百分百开源免费,可用于任意商业项目!</strong> <small class="text-info">使用高效的HDPHP框架构建</small></a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="http://www.houdunwang.com" target="_blank">培训</a></li>
                        <li><a href="http://www.hdcms.com" target="_blank">官网</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="panel-body">
            <div class="col-xs-3">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-danger active" style="width: 20%;">
                        20%
                    </div>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><strong>安装步骤</strong></li>
                    <li class="list-group-item" style="background: #dff0d8"><i class="fa fa-copyright"></i> 安装协议</li>
                    <li class="list-group-item"><i class="fa fa-pencil-square-o"></i> 环境检测</li>
                    <li class="list-group-item"><i class="fa fa-database"></i> 数据库配置</li>
                    <li class="list-group-item"><i class="fa fa-cloud-download"></i> 下载软件包</li>
                    <li class="list-group-item"><i class="fa fa-check-circle"></i> 完成</li>
                </ul>
            </div>
            <div class="col-xs-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">安装协议</h3>
                    </div>
                    <div class="panel-body">
                        <div class="license" style="text-indent: 2em;line-height: 1.5em;height: 500px;overflow: auto;padding: 10px;">
                            <p>HDCMS V2.0 安装协议</p>
                            <p>版权所有 (c) 2013-2019，北京后盾计算机技术培训有限责任公司。</p>

                            <p>感谢您选择HDCMS，希望我们的努力能为您提供一个简单、强大的站点解决方案。官方网址为 http://www.hdcms.com。</p>

                            <p>用户须知：本协议是您与后盾公司之间关于您使用HDCMS产品及服务的法律协议。无论您是个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，包括免除或者限制后盾责任的免责条款及对您的权利限制。请您审阅并接受或不接受本服务条款。如您不同意本服务条款及/或后盾随时对其的修改，您应不使用或主动取消HDCMS产品。否则，您的任何对HDCMS的相关服务的注册、登陆、下载、查看等使用行为将被视为您对本服务条款全部的完全接受，包括接受后盾对服务条款随时所做的任何修改。</p>

                            <p>本服务条款一旦发生变更, 后盾将在产品官网上公布修改内容。修改后的服务条款一旦在网站公布即有效代替原来的服务条款。您可随时登陆官网查阅最新版服务条款。如果您选择接受本条款，即表示您同意接受协议各项条件的约束。如果您不同意本服务条款，则不能获得使用本服务的权利。您若有违反本条款规定，后盾公司有权随时中止或终止您对HDCMS产品的使用资格并保留追究相关法律责任的权利。</p>

                            <p>在理解、同意、并遵守本协议的全部条款后，方可开始使用HDCMS产品。您也可能与后盾公司直接签订另一书面协议，以补充或者取代本协议的全部或者任何部分。</p>

                            <p>后盾公司拥有HDCMS的全部知识产权，包括商标和著作权。本软件只供许可协议，并非出售。后盾只允许您在遵守本协议各项条款的情况下复制、下载、安装、使用或者以其他方式受益于本软件的功能或者知识产权。</p>

                            <p>HDCMS遵循Apache Licence2开源协议，并且免费使用（但不包括其衍生产品、插件或者服务）。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重原作者的著作权，允许代码修改，再作为开源或商业软件发布。需要满足的条件：</p>
                            <p>1． 需要给用户一份Apache Licence ；</p>
                            <p>2． 如果你修改了代码，需要在被修改的文件中说明；</p>
                            <p>3． 在延伸的代码中（修改和有源代码衍生的代码中）需要带有原来代码中的协议，商标，专利声明和其他原来作者规定需要包含的说明；</p>
                            <p>4． 如果再发布的产品中包含一个Notice文件，则在Notice文件中需要带有本协议内容。你可以在Notice中增加自己的许可，但不可以表现为对Apache Licence构成更改。</p>
                        </div>
                    </div>
                </div>
                <a href="?a=environment" class="btn btn-primary">继续</a>
            </div>
        </div>
    </div>
    <div class="text-center" style="color:#fff;"> ©2010 - 2019 hdcms.com Inc.</div>
</div>
</body>
</html>
str;

session_start();
set_time_limit( 0 );
error_reporting( 0 );
header( "Content-type:text/html;charset=utf-8" );
if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
	die( 'HDCMS 需要PHP版本大于php5.4' . PHP_VERSION );
}
$action = isset( $_GET['a'] ) ? $_GET['a'] : 'copyright';
//软件包地址
$download_file_url = 'http://www.hdcms.com/?a=cloud/GetHdcms&m=store&t=web&siteid=1';
$last_version_url  = 'http://www.hdcms.com/?a=cloud/GetLastHdcms&m=store&t=web&siteid=1';
//版权信息
if ( $action == 'copyright' ) {
	$content = isset( $copyright ) ? $copyright : file_get_contents( 'copyright.html' );
	echo $content;
	exit;
}
//环境检测
if ( $action == 'environment' ) {
	//获取新新版本
	if ( ! is_dir( 'app' ) && ! $soft = curl_get( $last_version_url ) ) {
		echo '请求HDCMS云主机失败';
		exit;
	}
	//系统信息
	$data['PHP_OS']              = PHP_OS;
	$data['SERVER_SOFTWARE']     = $_SERVER['SERVER_SOFTWARE'];
	$data['PHP_VERSION']         = PHP_VERSION;
	$data['upload_max_filesize'] = get_cfg_var( "upload_max_filesize" ) ? get_cfg_var( "upload_max_filesize" ) : "不允许上传附件";
	$data['max_execution_time']  = get_cfg_var( "max_execution_time" ) . "秒 ";
	$data['memory_limit']        = get_cfg_var( "memory_limit" ) ? get_cfg_var( "memory_limit" ) : "0";
	//运行环境
	$data['h_PHP_VERSION'] = PHP_VERSION;
	$data['h_mysql']       = extension_loaded( 'mysql' ) ? '<i class="fa fa-check-circle fa-1x alert-success"></i>' : '<i class="fa fa-times-circle alert-danger"></i>';
	$data['h_Pdo']         = extension_loaded( 'Pdo' ) ? '<i class="fa fa-check-circle fa-1x alert-success"></i>' : '<i class="fa fa-times-circle alert-danger"></i>';
	$data['h_Gd']          = extension_loaded( 'Gd' ) ? '<i class="fa fa-check-circle fa-1x alert-success"></i>' : '<i class="fa fa-times-circle alert-danger"></i>';
	$data['h_curl']        = extension_loaded( 'curl' ) ? '<i class="fa fa-check-circle fa-1x alert-success"></i>' : '<i class="fa fa-times-circle alert-danger"></i>';
	$data['h_openSSL']     = extension_loaded( 'openSSL' ) ? '<i class="fa fa-check-circle fa-1x alert-success"></i>' : '<i class="fa fa-times-circle alert-danger"></i>';
	//目录状态
	$data['d_root'] = is_writable( '.' ) ? '<i class="fa fa-check-circle fa-1x alert-success"></i>' : '<i class="fa fa-times-circle alert-danger"></i>';
	$content        = isset( $environment ) ? $environment : file_get_contents( 'environment.html' );
	foreach ( $data as $t => $v ) {
		$content = str_replace( "{hd:{$t}}", $v, $content );
	}
	echo $content;
	exit;
}
//执行安装
if ( $action == 'database' ) {
	if ( ! empty( $_POST ) ) {
		//测试数据库连接
		$_SESSION['config'] = $_POST;
		$host               = $_SESSION['config']['host'];
		$username           = $_SESSION['config']['user'];
		$password           = $_SESSION['config']['password'];
		$dbname             = $_SESSION['config']['database'];
		if ( ! mysql_connect( $host, $username, $password ) ) {
			echo json_encode( [ 'valid' => 0, 'message' => '连接失败,请检查帐号与密码' ] );
			exit;
		}
		//数据库
		if ( ! mysql_select_db( $dbname ) ) {
			if ( ! mysql_query( "CREATE DATABASE $dbname CHARSET UTF8" ) ) {
				echo json_encode( [ 'valid' => 0, 'message' => '创建数据库失败' ] );
				exit;
			}
		}
		echo json_encode( [ 'valid' => 1, 'message' => '连接成功' ] );
		exit;
	}
	$content = isset( $database ) ? $database : file_get_contents( 'database.html' );
	echo $content;
	exit;
}
//环境检测
if ( $action == 'download' ) {
	$content = isset( $download ) ? $download : file_get_contents( 'download.html' );
	echo $content;
	exit;
}

//远程下载文件
if ( $action == 'downloadFile' ) {
	if ( is_dir( 'app' ) ) {
		//完整版时
		echo 1;
		exit;
	} else {
		$d = curl_get( $download_file_url );
		if ( strlen( $d ) < 2787715 ) {
			//下载失败
			exit;
		}
		$zipFile = 'hdcms.zip';
		file_put_contents( $zipFile, $d );
		//解包
		get_zip_originalsize( $zipFile, './' );
		function dcopy( $old, $new ) {
			is_dir( $new ) or mkdir( $new, 0755, TRUE );
			foreach ( glob( $old . '/*' ) as $v ) {
				if ( $v != 'upload/install.php' ) {
					$to = $new . '/' . basename( $v );
					is_file( $v ) ? copy( $v, $to ) : dcopy( $v, $to );
				}
			}

			return TRUE;
		}

		dcopy( 'upload', '.' );
		//删除目录
		function del( $dir ) {
			if ( ! is_dir( $dir ) ) {
				return TRUE;
			}
			foreach ( glob( $dir . "/*" ) as $v ) {
				is_dir( $v ) ? del( $v ) : unlink( $v );
			}

			return rmdir( $dir );
		}

		del( 'upload' );
		echo 1;
		exit;
	}
}

//安装完成,添加数据
if ( $action == 'table' ) {
	//添加数据表
	$dsn      = "mysql:host={$_SESSION['config']['host']};dbname={$_SESSION['config']['database']}";
	$username = $_SESSION['config']['user'];
	$password = $_SESSION['config']['password'];
	$pdo      = new Pdo( $dsn, $username, $password, [ PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'" ] );
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	//执行建表语句
	if ( is_file( 'data/install.sql' ) ) {
		$sql = file_get_contents( 'data/install.sql' );
		$sql = preg_replace( '/^(\/\*|#.*).*/m', '', $sql );
		//替换表前缀
		$sql    = str_replace( '`hd_', '`' . $_SESSION['config']['prefix'], $sql );
		$result = preg_split( '/;(\r|\n)/is', $sql );
		foreach ( (array) $result as $r ) {
			if ( preg_match( '/^\s*[a-z]/i', $r ) ) {
				try {
					$pdo->exec( $r );
				} catch ( PDOException $e ) {
					die( 'SQL执于失败:' . $r . '. ' . $e->getMessage() );
				}
			}
		}
	}
	//更新系统版本号
	$version = include 'data/upgrade.php';
	$sql
	         = "INSERT INTO {$_SESSION['config']['prefix']}cloud (uid,username,webname,AppSecret,versionCode,releaseCode,createtime)
		VALUES(0,'','','','{$version['versionCode']}','{$version['releaseCode']}',0)";
	try {
		$pdo->exec( $sql );
	} catch ( PDOException $e ) {
		die( 'SQL执于失败:' . $sql . '. ' . $e->getMessage() );
	}

	//设置管理员帐号
	$user     = $pdo->query( "select * from {$_SESSION['config']['prefix']}user where uid=1" );
	$row      = $user->fetchAll( PDO::FETCH_ASSOC );
	$password = md5( $_SESSION['config']['upassword'] . $row[0]['security'] );
	$pdo->exec( "UPDATE {$_SESSION['config']['prefix']}user SET password='{$password}' WHERE uid=1" );
	//修改配置文件
	file_put_contents( 'data/database.php', '<?php return [];?>' );
	$data = array_merge( include 'system/config/database.php', $_SESSION['config'] );
	file_put_contents( 'data/database.php', '<?php return ' . var_export( $data, TRUE ) . ';?>' );
	header( 'Location:?a=finish' );
}
if ( $action == 'finish' ) {
	//清除运行数据
	foreach ( glob( 'data/*' ) as $f ) {
		if ( ! in_array( basename( $f ), [ 'database.php', 'upgrade.php' ] ) ) {
			@unlink( $f );
		}
	}
	foreach ( glob( 'install/*' ) as $f ) {
		@unlink( $f );
	}
	is_dir( 'install' ) and rmdir( 'install' );
	//删除下载的压缩包
	@unlink( 'hdcms.zip' );
	@unlink( 'install.php' );
	//显示界面
	$content = isset( $finish ) ? $finish : file_get_contents( 'finish.html' );
	echo $content;
	exit;
}
//编译install.php安装文件,主要是将模板整合进来
if ( $action == 'compile' ) {
	$tpl     = [ 'copyright', 'environment', 'database', 'download', 'finish' ];
	$content = substr( file_get_contents( 'install.php' ), 5 );
	foreach ( $tpl as $t ) {
		$content = '$' . $t . "=<<<str\n" . file_get_contents( $t . '.html' ) . "\nstr;\n" . $content;
	}
	file_put_contents( '../install.php', "<?php \n" . $content );
	echo "<h1>编译成功</h1>";
}

function curl_get( $url ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );

	if ( ! curl_exec( $ch ) ) {
		$data = '';
	} else {
		$data = curl_multi_getcontent( $ch );
	}
	curl_close( $ch );

	return $data;
}

/**
 * 解压zip包
 *
 * @param string $filename 文件名
 * @param string $path 路径 如:./
 *
 * @return bool
 * 例 get_zip_originalsize( '20131101.zip', 'temp/' );
 */
function get_zip_originalsize( $filename, $path ) {
	//先判断待解压的文件是否存在
	if ( ! file_exists( $filename ) ) {
		die( "文件 $filename 不存在！" );
	}
	$starttime = explode( ' ', microtime() ); //解压开始的时间

	//将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
	$filename = iconv( "utf-8", "gb2312", $filename );
	$path     = iconv( "utf-8", "gb2312", $path );
	//打开压缩包
	$resource = zip_open( $filename );
	$i        = 1;
	//遍历读取压缩包里面的一个个文件
	while ( $dir_resource = zip_read( $resource ) ) {
		//如果能打开则继续
		if ( zip_entry_open( $resource, $dir_resource ) ) {
			//获取当前项目的名称,即压缩包里面当前对应的文件名
			$file_name = $path . zip_entry_name( $dir_resource );
			//以最后一个“/”分割,再用字符串截取出路径部分
			$file_path = substr( $file_name, 0, strrpos( $file_name, "/" ) );
			//如果路径不存在，则创建一个目录，true表示可以创建多级目录
			if ( ! is_dir( $file_path ) ) {
				mkdir( $file_path, 0777, TRUE );
			}
			//如果不是目录，则写入文件
			if ( ! is_dir( $file_name ) ) {
				//读取这个文件
				$file_size = zip_entry_filesize( $dir_resource );
				//最大读取6M，如果文件过大，跳过解压，继续下一个
				if ( $file_size < ( 1024 * 1024 * 6 ) ) {
					$file_content = zip_entry_read( $dir_resource, $file_size );
					file_put_contents( $file_name, $file_content );
				} else {
					echo "<p> " . $i ++ . " 此文件已被跳过，原因：文件过大， -> " . iconv( "gb2312", "utf-8", $file_name ) . " </p>";
				}
			}
			//关闭当前
			zip_entry_close( $dir_resource );
		}
	}
	//关闭压缩包
	zip_close( $resource );

	return TRUE;
}

