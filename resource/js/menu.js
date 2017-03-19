/**
 * 后台站点平台左侧菜单管理
 */
require(['util'], function (util) {
    //修改左侧菜单样式
    $('#' + util.get('mi')).addClass('active');
})
/**
 * 后台左侧菜单搜索
 * @param elem
 */
function search(elem) {
    require(['jquery'], function ($) {
        //搜索内容
        var con = $(elem).val();
        //让所有当前菜单先隐藏
        $(".left-menu .panel-heading").addClass('hide');
        $(".left-menu .list-group").addClass('hide');
        $("a").each(function () {
            if ($.trim($(this).text()).indexOf(con) >= 0) {
                $(this).parent().parent().removeClass('hide').prev().removeClass('hide');
                $(this).removeClass('hide');
            }
        });
    })
}