/**
 * 前台底部快捷菜单控制
 */
//样式一的弹出子菜单事件处理
require(['jquery'], function ($) {
    $('.quickmenu dt').click(function () {
        if ($(this).next('dd').attr('style')) {
            $(this).nextAll('dd').removeAttr('style');
        } else {
            $(this).nextAll('dd').css({'display': 'flex'});
        }
    })
})