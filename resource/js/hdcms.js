/*
 |--------------------------------------------------------------------------
 | HDCMS公共函数库
 |--------------------------------------------------------------------------
 */
define('hdcms', ['util', 'jquery'], function (util, $) {
    return {
        /**
         * 获取站点的用户列表
         * @param $callback 回调函数
         * @param filterUid 过滤掉的用户编号
         */
        getUsers: function (callback, filterUid) {
            require(['util'], function (util) {
                //站点编号
                var siteId = util.get('siteid');
                var modalObj = util.modal({
                    title: '选择用户',
                    width: 700,
                    footer: '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\
                <button type="button" class="btn btn-primary confirm" >确定</button>',
                    id: 'usersModal',
                    content: ["?s=system/component/users&single=0&siteid=" + siteId + "&filterUid=" + filterUid],
                    events: {
                        'confirm': function () {
                            var bt = $("#getUsers").find("button[class*='primary']");
                            var uid = [];
                            bt.each(function (i) {
                                uid.push($(this).attr('uid'));
                            })
                            callback(uid);
                        }
                    }
                })
            })
        },
        /**
         * 选择模块和模板
         * @param callback 选择后的回调函数
         * @param object opt 选项
         */
        getModuleTemplate: function (callback, opt) {
            var options = $.extend({
                module: [],
                template: []
            }, opt);
            url = '?s=system/component/ajaxModulesTemplate&module=' + options.module.join('|') + '&template=' + options.template.join('|');
            var modalobj = util.modal({
                id: 'modalList',
                content: [url],
                footer: '<button class="btn btn-primary confirm">确定</button>',
                events: {
                    confirm: function () {
                        //选取的模块和模板
                        var module = [];
                        var template = [];
                        $(modalobj).find('#ajaxModulesTemplate .btn-primary').each(function (i) {
                            var item = {title: $(this).attr('title'), name: $(this).attr('name')};
                            if ($(this).attr('mid') > 0) {
                                module.push(item);
                            } else {
                                template.push(item);
                            }
                        });
                        console.log(template);
                        callback({module: module, template: template});
                        modalobj.modal('hide');
                    }
                },

            })
        },
        /**
         * 选择系统菜单
         * @param callback
         */
        linkBrowser: function (callback) {
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
        },
        /**
         * 模块选择
         * @param callback
         * @param mid
         */
        moduleBrowser: function (callback, mid) {
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
        },
        /**
         * 验证关键词是否已经存在
         * @param content 关键词内容
         * @param rid 规则编号,编辑时使用用于排除这个规则号
         */
        checkWxKeyword: function (obj, rid, callback) {
            var con = $(obj).val();
            $.post('?s=site/keyword/checkWxKeyword', {content: con, rid: rid ? rid : 0}, function (res) {
                if ($.isFunction('callback')) {
                    callback(res);
                }
            }, 'json');
        }
    }
});