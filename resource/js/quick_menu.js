require(['bootstrapContextmenu'], function ($) {
    $('.currentMenu li').contextmenu({
        target:'#context-menu',
        before: function(e,context) {

        },
        onItem: function(context,e) {
            var obj  =$(context.context);
            var data={
                module:obj.attr('module'),
                url:obj.attr('url')+'&mark='+obj.attr('mark'),
                title:$.trim(obj.text())
            };
            $.post('?s=site/system/quickMenu',data,function(json){
                if(json['valid']==1){
                    util.message('添加菜单成功,需要刷新页面后才可以看到底部菜单。','','success');
                }
            },'JSON');
        }
    })

    //删除底部快捷菜单
    $(".close_quick_menu").click(function(){
        $.post('?s=site/system/quickMenuStatus',{'quickmenu':0},function(json){
            util.message('菜单关闭显示成功,下次要开启底部快捷菜单请在 [系统设置] 中进行开启<br/>需要刷新页面后才可以看到效果。','','success');
        },'JSON');
    })
})