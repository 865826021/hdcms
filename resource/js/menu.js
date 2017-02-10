/**
 * 后台站点平台左侧菜单管理
 * @type {{mark: string, menuid: string, bootstrap: Window.hdMenus.bootstrap, system: Window.hdMenus.system, search: Window.hdMenus.search}}
 */
window.hdMenus = {
    //菜单组标识
    mark: '',
    //菜单编号
    menuid: '',
    //初始化菜单
    bootstrap: function () {
        This = this;
        require(['jquery', 'util'], function ($, util) {
            This.setMark(util);
            This.setMenuId(util);
            //改变当前点击的顶部菜单选中样式
            $(".top_menu a[mark='" + This.mark + "']").parent().addClass('active');
            //设置左侧菜单按钮背景样式
            $("[menuid='" + This.menuid + "']").parent().addClass('active').find('a').css({color:'#fff'});
            //当mark为extModule时显示模块动作菜单按钮
            if (This.mark == 'package' && util.get('m')) {
                $('.module_action_type').removeClass('hide');
                var moduleActionType = sessionStorage.getItem('module_action_type');
                if (!moduleActionType) {
                    moduleActionType = 'default';
                    sessionStorage.setItem('module_action_type', 'default');
                }
                //按钮加背景色
                $('.module_action_type button').removeClass('btn-primary').addClass('btn-default');
                $('.module_action_type .' + moduleActionType).removeClass('btn-default').addClass('btn-primary');
                //初次展示时没有类型时设置为默认类型
                switch (moduleActionType) {
                    case 'default':
                        $(".module_action").removeClass('hide').addClass('currentMenu');
                        break;
                    case 'system':
                        $("[mark='package']").not('.module_action').removeClass('hide').addClass('currentMenu');
                        break;
                    case 'group':
                        $("[mark='package']").removeClass('hide').addClass('currentMenu');
                        break;
                }
            } else {
                //设置左侧系统菜单显示,并打上当前菜单标记
                $("[mark='" + This.mark + "']").removeClass('hide').addClass('currentMenu');
            }
        })
    },
    /**
     * 更改模块动作类型
     * @param type default system group三种类型
     */
    changeModuleActionType: function (type) {
        sessionStorage.setItem('module_action_type', type);
        location.reload(true);
    },
    /**
     * 获取菜单组标识
     * 菜单标识主要用来决定显示哪个左侧菜单
     * @param util util组件
     */
    setMark: function (util) {
        var mark = util.get('mark');
        mark = mark?mark.replace(/[^a-z]/i,''):mark;
        if (!mark) {
            mark = sessionStorage.getItem('hdMenusMark');
            if (!mark) {
                mark = localStorage.getItem('hdMenusMark');
            }
        }
        this.mark = mark;
        sessionStorage.setItem('hdMenusMark', mark);
        localStorage.setItem('hdMenusMark', mark);
    },
    /**
     * 当前点击菜单编号
     * 系统会为左侧的菜单包括模块菜单都设置编号
     * menuid 主要用来设置当前点击菜单的背景色
     * @param util
     */
    setMenuId: function (util) {
        var menuid = sessionStorage.getItem('hdMenusmenuId');
        if (!menuid) {
            menuid = localStorage.getItem('hdMenusmenuId');
        }
        sessionStorage.setItem('hdMenusmenuId', menuid);
        localStorage.setItem('hdMenusmenuId', menuid);
        this.menuid = menuid;
    },
    //系统菜单事件
    system: function (elem) {
        this.menuid = $(elem).attr('menuid');
        this.mark = $(elem).attr('mark');
        var url = $(elem).attr('url');
        sessionStorage.setItem('hdMenusmenuId', this.menuid);
        sessionStorage.setItem('hdMenusMark', this.mark);
        location.href = url + '&mark=' + this.mark;
    },
    //搜索菜单
    search: function (elem) {
        //搜索内容
        var con = $(elem).val();
        //让所有当前菜单先隐藏
        $(".currentMenu").addClass('hide');
        $("a.currentMenu").each(function () {
            if ($.trim($(this).text()).indexOf(con) >= 0) {
                $(this).parent().parent().removeClass('hide').prev('.currentMenu').eq(0).removeClass('hide');
                $(this).removeClass('hide');
            }
        });
        if (con == '')
            $(".currentMenu").removeClass('hide');
    }
}
//初始化
hdMenus.bootstrap();