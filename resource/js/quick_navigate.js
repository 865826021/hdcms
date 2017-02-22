/**
 * 后台底部快捷导航菜单控制
 */
require(['bootstrapContextmenu'], function ($) {
    //添加菜单
    $('.currentMenu a[url]').contextmenu({
        target: '#context-menu',
        before: function (e, context) {

        },
        onItem: function (context, e) {
            var obj = $(context.context);
            var data = {
                module: obj.attr('module'),
                url: obj.attr('url') + '&mark=' + obj.attr('mark'),
                title: $.trim(obj.text())
            };
            $.post('?m=quicknavigate&action=controller/site/post', data, function (json) {
                if (json['valid'] == 1) {
                    util.message('添加菜单成功,系统将刷新页面。', window.system.url, 'success', 1);
                }
            }, 'JSON');
        }
    })
    //删除菜单
    $('.quick_navigate a').contextmenu({
        target: '#context-menu-del',
        before: function (e, context) {
        },
        onItem: function (context, e) {
            var obj = $(context.context);
            var data = {
                url: obj.attr('href')
            };
            $.post('?m=quicknavigate&action=controller/site/del', {url: data.url}, function (json) {
                if (json['valid'] == 1) {
                    util.message('删除菜单成功,系统将刷新页面。', window.system.url, 'success', 1);
                }
            }, 'JSON');
        }
    })

    //删除底部快捷菜单
    $(".close_quick_menu").click(function () {
        $.post('?m=quicknavigate&action=controller/site/status', {'quickmenu': 0}, function (json) {
            util.message('菜单关闭显示成功,下次要开启底部快捷菜单请在 [系统设置] 中进行开启。', 'refresh', 'success');
        }, 'JSON');
    })
})