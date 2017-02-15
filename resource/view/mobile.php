<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<if value="IS_WEIXIN">
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	</if>
	<link href="{{__ROOT__}}/node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{__ROOT__}}/node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="{{__ROOT__}}/node_modules/hdjs/app/util.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/require.js"></script>
	<script>
		//HDJS组件需要的配置
		hdjs = {
			'base': 'node_modules/hdjs',
			'uploader': '{{u("system/component/uploader")}}',
			'filesLists': '{{u("system/component/filesLists")}}',
			'removeImage': '{{u("system/component/removeImage")}}',
		};
		window.system = {
			attachment: "{{__ROOT__}}/attachment",
			user: <?php echo json_encode( v('member.info') );?>,
			root: "<?php echo __ROOT__;?>",
			url: "{{__URL__}}",
			siteid: <?php echo SITEID;?>,
			module: "<?php echo v( 'module.name' );?>",
			//用于上传等组件使用标识当前是后台用户
			user_type: 'member'
		}
	</script>
	<script src="{{__ROOT__}}/node_modules/hdjs/config.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/js/jquery.min.js"></script>
	<script src="{{__ROOT__}}/resource/js/hdcms.js"></script>
	<link href="{{__ROOT__}}/resource/css/hdcms.css" rel="stylesheet">
	<script>
		require(['jquery'], function ($) {
			//为异步请求设置CSRF令牌
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		})
		if (navigator.appName == 'Microsoft Internet Explorer') {
			if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
				alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
			}
		}
	</script>
</head>
<body>
<blade name="content"/>
</body>
</html>