<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>{{v('site.info.name')}} - HDCMS开源免费内容管理系统</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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
			root: "{{__ROOT__}}",
			url: "{{__URL__}}",
			siteid: "{{SITEID}}",
			module: "{{v( 'module.name' )}}",
			//用于上传等组件使用标识当前是后台用户
			user_type: 'user'
		}
	</script>
	<link href="{{__ROOT__}}/node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{__ROOT__}}/node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="{{__ROOT__}}/node_modules/hdjs/app/util.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/require.js"></script>
	<script src="{{__ROOT__}}/node_modules/hdjs/config.js"></script>
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
	</script>
</head>
<body>

</body>
</html>