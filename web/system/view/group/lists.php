<extend file="resource/view/system"/>
<block name="content">
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i></li>
        <li><a href="?s=system/manage/menu">系统</a></li>
        <li class="active">已经安装模块</li>
    </ol>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="{{u('lists')}}">用户组列表</a></li>
        <li role="presentation"><a href="{{u('post')}}">添加用户组</a></li>
    </ul>
    <form action="?s=system/group/remove" class="form-horizontal" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">可使用的公众服务套餐</h3>
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="30">删？</th>
                        <th>名称</th>
                        <th>公众号数量</th>
                        <th>有效期限</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <foreach from="$groups" value="$g">
                        <tr>
                            <td>
                                <input type="checkbox" name="id[]" value="{{$g['id']}}">
                            </td>
                            <td>
                                {{$g['name']}}
                            </td>
                            <td>{{$g['maxsite']}}</td>
                            <td>
                                <if value="$g['daylimit']">
                                    <span class="label label-danger">{{$g['daylimit']}}天</span>
                                    <else/>
                                    <span class="label label-success">永久有效</span>
                                </if>
                            </td>
                            <td>
                                <a href="?s=system/group/post&id={{$g['id']}}">编辑</a>
                            </td>
                        </tr>
                    </foreach>
                    </tbody>
                </table>
            </div>
        </div>
        <button class="btn btn-primary">删除选中用户组</button>
    </form>
</block>
