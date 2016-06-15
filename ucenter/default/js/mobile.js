//快捷菜单1 弹出子菜单事件处理
$(function () {
    $('body').delegate('.quickmenu-style1 li','click',function(){
        if ($(this).find('.sub-menus:hidden').length) {
            $('.quickmenu-style1 li .sub-menus').hide();
            $(this).find('.sub-menus:hidden').show();
        } else {
            $('.quickmenu-style1 li .sub-menus').hide();
            $(this).find('.sub-menus:hidden').hide()
        }
    })
});

//顶部导航菜单显示子栏目
$(function () {
    $(".cat_menu_list").click(function () {
        if ($(".child_menu_list:hidden").length) {
            $(".child_menu_list").slideDown('fast');
            $('.head-bg-gray').show();
        } else {
            $(".child_menu_list").slideUp('fast');
            $('.head-bg-gray').hide();
        }
    });
    $(".head-bg-gray").click(function(){
        $('.child_menu_list').hide();
        $('.head-bg-gray').hide();
    })
});
