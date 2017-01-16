$(function () {
    //链接跳转
    $("[dataHref]").click(function (event) {
        var url = $(this).attr('dataHref');
        //记录当前点击的菜单
        sessionStorage.setItem('dataHref', url);
        if ($(this).attr('menuid')) {
            sessionStorage.setItem('menuid', $(this).attr('menuid'));
        }
        location.href = url + '&menuid=' + sessionStorage.getItem('menuid');
        //阻止冒泡
        event.stopPropagation();
    });
    //记录顶级菜单编号
    if (!sessionStorage.getItem('menuid')) {
        sessionStorage.setItem('menuid', "{{key($_LINKS_['menus'])}}");
    }
    //设置顶级菜单为选中样式
    if (sessionStorage.getItem('menuid')) {
        $("#top_menu_" + sessionStorage.getItem('menuid')).addClass('active');
    }
    //设置左侧菜单点击样式
    if (sessionStorage.getItem('dataHref')) {
        $("li[dataHref='" + sessionStorage.getItem('dataHref') + "']").addClass('active');
    }
    //更改模块展示菜单形式 1 默认 2 系统 3 复合
    function changeModuleActionType(type) {
        sessionStorage.setItem('moduleActionType', type);
        location.reload(true);
    }

    //有模块访问时
    if (window.system.module && sessionStorage.getItem('menuid') == 21) {
        //显示模块展示菜单形式 默认/系统/组合
        $('.menu_action_type').removeClass('hide');
        //模块动作类型 1 默认 2 系统 3 复合
        moduleActionType = sessionStorage.getItem('moduleActionType');
        if (!moduleActionType) {
            moduleActionType = 1;
        }
        //设置点击按钮为蓝色
        $('.menu_action_type button').eq(moduleActionType - 1).addClass('btn-primary');
        switch (moduleActionType * 1) {
            case 1:
                //默认类型
                $('.module_active').removeClass('hide');
                $('.module_back').removeClass('hide');
                break;
            case 2:
                //系统类型
                $('.module_lists').removeClass('hide');
                $("[menuid='" + sessionStorage.getItem('menuid') + "']").removeClass('hide');
                break;
            case 3:
                //组合类型
                $('.module_active').removeClass('hide');
                $('.module_back').removeClass('hide');
                $('.module_lists').removeClass('hide');
                break;
        }
    } else {
        //显示当前左侧菜单
        $("[menuid='" + sessionStorage.getItem('menuid') + "']").removeClass('hide');
        if (sessionStorage.getItem('menuid') == 21) {
            $('.module_lists').removeClass('hide');
        }
    }
});

//搜索菜单
function searchMenu(obj) {
    //搜索内容
    var con = $(obj).val();
    var menuid = sessionStorage.getItem('menuid');
    $("[menuid='" + sessionStorage.getItem('menuid') + "']").addClass('hide');
    $("#menus li[menuid=" + menuid + "]").each(function () {
        if ($.trim($(this).text()).indexOf(con) >= 0) {
            hasFind = true;
            console.log($(this).parent().html());
            $(this).parent().removeClass('hide').prev().removeClass('hide');
            $(this).removeClass('hide');
        }
    });
    if (con == '')
        $("[menuid='" + sessionStorage.getItem('menuid') + "']").removeClass('hide');
}