//快捷样式一,弹出子菜单事件处理
$(function () {
    $('body').delegate('.quickmenu_normal ul li a','click',function(){
        if ($(this).next('.sub-menus:hidden').length) {
            $('.quickmenu_normal li .sub-menus').hide();
            $(this).next('.sub-menus:hidden').show();
        } else {
            $('.quickmenu_normal li .sub-menus').hide();
            $(this).next('.sub-menus:hidden').hide()
        }
    })
});