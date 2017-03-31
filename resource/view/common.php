<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<link href="{{__ROOT__}}/node_modules/hdjs/css/bootstrap.min.css" rel="stylesheet">
<link href="{{__ROOT__}}/node_modules/hdjs/css/font-awesome.min.css" rel="stylesheet">
<script>
	//HDJS组件需要的配置
	hdjs = {
		'base': '{{__ROOT__}}/node_modules/hdjs',
		'uploader': '{{u("component/upload/uploader",["m"=>Request::get("m")])}}',
		'filesLists': '{{u("component/upload/filesLists",["m"=>Request::get("m")])}}',
		'removeImage': '{{u("component/upload/removeImage",["m"=>Request::get("m")])}}',
		'ossSign': '{{u("component/oss/sign",["m"=>Request::get("m")])}}',
	};
	window.system = {
		attachment: "{{__ROOT__}}/attachment",
		root: "{{__ROOT__}}",
		url: "{{__URL__}}",
		siteid: "{{SITEID}}",
		module: "{{v( 'module.name' )}}",
		//用于上传等组件使用标识当前是后台用户
		user_type: '{{v("user")?"user":"member"}}'
	}
</script>
<script>
	if (navigator.appName == 'Microsoft Internet Explorer') {
		if (navigator.userAgent.indexOf("MSIE 5.0") > 0 || navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) {
			alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
		}
	}
</script>