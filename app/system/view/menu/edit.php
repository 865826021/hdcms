<extend file="resource/view/system"/>
<link rel="stylesheet" href="{{__VIEW__}}/menu/css.css">
<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">菜单列表</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#">菜单列表</a></li>
    </ul>
    <h5 class="page-header">菜单列表</h5>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        系统内置的菜单不允许修改 "链接地址"，不允许切换 "显示状态", 不允许 "删除"
    </div>
    <form action="" method="post" id="form" class="ng-cloak">
        <div class="panel panel-default" ng-controller="ctrl">
            <div class="panel-body table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="80">排序</th>
                        <th width="300">名称</th>
                        <th width="120">标识</th>
                        <th width="180">图标</th>
                        <th width="180">链接地址</th>
                        <th>显示</th>
                        <th>系统</th>
                        <th width="180">子链接</th>
                        <th width="80">操作</th>
                    </tr>
                    </thead>
                    <tbody ng-repeat="(k,v) in menus">
                    <tr>
                        <td><input type="text" ng-model="v.orderby" class="form-control"></td>
                        <td>
                            <div ng-class="{pill:v._level>1}" ng-style="v._level==3?{'margin-left': 50}:''">
                                <input type="text" ng-model="v.title" class="form-control" value="基础设置" placeholder="@{{v.placeholder}}" style="width:150px;">
                            </div>
                        </td>
                        <td>
                            <span ng-if="!v.new_add || v._level!=1" ng-bind="v.mark"></span>
                            <input type="text" ng-if="v.new_add && v._level==1" ng-model="v.mark" class="form-control">
                        </td>
                        <td>
                            <div class="input-group" ng-if="v._level==1">
                                <input type="text" class="form-control" ng-model="v.icon">
                                <span class="input-group-addon"><i ng-class="v.icon"></i></span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default ico-modal" type="button">图标</button>
                                </span>
                            </div>
                            <span ng-if="!v.new_add && v._level!=1" ng-bind="v.permission"></span>
                            <input type="text" ng-if="v.new_add && v._level==3" ng-model="v.permission" class="form-control" placeholder="权限标识">
                        </td>
                        <td>
                            <input type="text" ng-if="v.new_add && v._level==3" ng-model="v.url" class="form-control" placeholder="链接地址">
                        </td>
                        <td>
                            <span class="btn btn-success" title="点击切换" ng-click="changeDisplayStatus(v.id,0)" ng-if="v.is_display==1 && !v.new_add">显示</span>
                            <span class="btn btn-danger" title="点击切换" ng-click="changeDisplayStatus(v.id,1)" ng-if="v.is_display==0 && !v.new_add">隐藏</span>
                        </td>
                        <td>
                            <span class="label label-danger" ng-if="v.is_system==1 && !v.new_add">系统内置</span>
                            <span class="label label-success" ng-if="v.is_system==0 && !v.new_add">自定义</span>
                        </td>
                        <td>
                            <input type="text" ng-if="v.new_add && v._level==3" ng-model="v.append_url" class="form-control" placeholder="子链接地址">
                        </td>
                        <td>
                            <span class="btn btn-danger system" ng-if="!v.new_add && v.is_system==0" title="删除菜单" ng-click="delMenu(v)">删除</span>&nbsp;
                            <i class="fa fa-times-circle" ng-if="v.new_add" style="cursor: pointer" onclick="$(this).parents('tr').eq(0).remove();"></i>
                        </td>
                    </tr>
                    <tr ng-if="(v._level==3 && menus[k+1]._level!=3) || (v._level==2 && v.id && menus[k+1]._level !=3)">
                        <td>&nbsp;</td>
                        <td colspan="8">
                            <div class="pill-bottom" style="margin-left:50px;cursor: pointer;" ng-click="addMenu(v,3)">
                                &nbsp;&nbsp;<i class="fa fa-plus-circle"></i> 添加菜单
                            </div>
                        </td>
                    </tr>
                    <tr ng-if="(menus[k+1]._level ==1 || !menus[k+1]) && v.pid">
                        <td>&nbsp;</td>
                        <td colspan="8">
                            <div class="pill-bottom" style="cursor: pointer;" ng-click="addMenu(v,2)">
                                &nbsp;&nbsp;<i class="fa fa-plus-circle"></i> 添加菜单组
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="8">
                            <div class="add add_level0" style="cursor: pointer;" ng-click="addMenu(v,1)"><i class="fa fa-plus-circle"></i> 添加顶级分类</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
        <input type="hidden" name="menu"/>
    </form>
</block>

<script>
    require(['angular', 'util','underscore'], function (angular, util,_) {
        //显示字体模态框
        $("table").delegate('.ico-modal', 'click', function () {
            var div = $(this).parents('div').eq(0);
            util.font(function (ico) {
                div.find('input').val(ico);
                div.find('i').attr('class', ico);
            });
        });

        angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
            $scope.menus = {{$menus}};
            //更改菜单的显示状态
            $scope.changeDisplayStatus = function (id, state) {
                $.post("{{u('changeDisplayState')}}", {id: id, is_display: state}, function () {
                    location.reload(true);
                })
            }
            //添加菜单
            $scope.addMenu = function (item, level) {
                var placeholder = ['顶级分类名称', '菜单组名称', '菜单名称'];
                var data = {
                    id: false,
                    pid: 0,
                    title: '',
                    permission: '',
                    url: '',
                    append_url: '',
                    icon: 'fa fa-cubes',
                    orderby: 0,
                    _level: level,
                    is_display: 1,
                    is_system: 0,
                    new_add: 1,
                    mark: '',
                    placeholder: placeholder[level - 1]
                };
                switch (level) {
                    case 1:
                        $scope.menus.push(data);
                        break;
                    case 2:
                        data.mark=item.mark;
                        if(item._level==3){
                            angular.forEach($scope.menus,function(value,key){
                                if(value.id==item.pid){
                                    data.pid = value.pid;
                                    return;
                                }
                            });
                        }else{
                            data.pid = item._level==1?item.id:item.pid;
                        }
                        $scope.menus.splice(_.indexOf($scope.menus,item)+1, 0, data);
                        break;
                    case 3:
                        data.mark=item.mark;
                        data.pid = item._level==3?item.pid:item.id;
                        $scope.menus.splice(_.indexOf($scope.menus,item)+1, 0, data);
                        break;
                }
            };

            //删除菜单
            $scope.delMenu = function (item) {
                util.confirm('删除菜单将删除当前菜单及所有子菜单,确定删除吗?',function(){
                    $.post("{{u('delMenu')}}", {id: item.id}, function (res) {
                        if(res.valid==1){
                            util.message(res.message,'refresh','success');
                        }else{
                            util.message(res.message,'','error');
                        }
                    },'json')
                })
            }

            //提交表单
            $("#form").submit(function () {
                $("[name='menu']").val(angular.toJson($scope.menus));
                return true;
            })
        }]);
        angular.bootstrap(document.getElementById('form'), ['app']);
    });
</script>

<style>
    .table > tbody, .table > tbody + tbody {
        border-top : none;
    }
</style>