/*
 |--------------------------------------------------------------------------
 | HDCMS公共函数库
 |--------------------------------------------------------------------------
 */
define('hdcms', ['bootstrap'], function () {
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
                    id: 'usersModal',
                    content: ["?s=system/component/users&single=0&siteid=" + siteId + "&filterUid=" + filterUid],
                    events: {
                        'hidden.bs.modal': function () {
                            var bt = $("#getUsers").find("button[class*='primary']");
                            var uid = [];
                            bt.each(function (i) {
                                uid.push($(this).attr('uid'));
                            })
                            callback(uid);
                            //删除模态
                            modalObj.remove();
                        }
                    }
                })
            })
        }
    }
});