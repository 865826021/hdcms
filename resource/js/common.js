/**
 * 公共JS库
 * @author 向军
 * <2300071698@qq.com>
 */
//checkbox滑动插件
$(function () {
    require(['bootstrap.switch'], function ($) {
        $(".bootstrap-switch").bootstrapSwitch();
    })
})
require(['util'], function (util) {
    ////显示系统菜单
    util.linkBrowser = function (callback) {
        var modalobj = util.modal({
            content: ['?s=system/component/linkBrowser'],
            title: '请选择链接',
            width: 600,
            show: true,//直接显示
            footer: '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>'
        });
        window.selectLinkComplete = function (link) {
            if ($.isFunction(callback)) {
                callback(link);
                modalobj.modal('hide');
            }
        }
    }

    //模块选择
    util.moduleBrowser = function (callback, mid) {
        var modalobj = util.modal({
            content: ['?s=system/component/moduleBrowser&mid=' + mid],
            title: '请选择模块',
            width: 600,
            show: true,//直接显示
            footer: '<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="confirmModuleSelectHandler()">确定</button>'
        });
        modalobj.on('hidden.bs.modal', function () {
            modalobj.remove();
        });
        window.selectModuleComplete = function (link) {
            if ($.isFunction(callback)) {
                callback(link);
            }
        }
    }

    /**
     * 验证关键词是否已经存在
     * @param obj 文本框
     * @param rid 规则编号
     */
    util.checkWxKeyword = function (obj, rid, callback) {
        var con = $(obj).val();
        $.post('?s=site/keyword/checkWxKeyword', {content: con, rid: rid ? rid : 0}, function (res) {
            if (res.valid == 0) {
                var str = '';
                switch (res.message.module) {
                    case 'basic':
                        //普通文本回复
                        str = "该关键字已存在于 <a href='?s=site/reply/post&m=basic&rid=" + res.message.rid + "&m=basic'>" +
                            "文本消息 \"" + res.message.name + "\"</a> 规则中";
                        break;
                    case 'news':
                        //普通文本回复
                        str = "该关键字已存在于 <a href='?s=site/reply/post&m=news&rid=" + res.message.rid + "&m=basic'>" +
                            "图文消息 \"" + res.message.name + "\"</a> 规则中";
                }
                $(obj).next().html(str);
            } else {
                $(obj).next().html('');
            }
            if ($.isFunction('callback')) {
                callback(res);
            }
        }, 'json');
    }
});

