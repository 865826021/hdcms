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