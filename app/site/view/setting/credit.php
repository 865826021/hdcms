<extend file="resource/view/site"/>
<block name="content">
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{url('site/credit')}}">积分列表</a></li>
        <li><a href="{{url('site/tactics')}}">积分策略</a></li>
    </ul>
    <form action="" method="post" onsubmit="post(event)">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>启用否？</th>
                        <th>积分</th>
                        <th>积分名称</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <input type="checkbox" name="creditnames[credit1][status]" value="1" checked="checked"
                                   disabled="disabled">
                        </td>
                        <td>credit1</td>
                        <td>
                            <input type="text" name="creditnames[credit1][title]"
                                   value="{{$creditnames['credit1']['title']}}" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="creditnames[credit2][status]" value="1" checked="checked"
                                   disabled="disabled">
                        </td>
                        <td>credit2</td>
                        <td>
                            <input type="text" name="creditnames[credit2][title]"
                                   value="{{$creditnames['credit2']['title']}}" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="creditnames[credit3][status]" value="1" {{$creditnames['credit3']['status']?'checked="checked"':''}}>
                        </td>
                        <td>credit3</td>
                        <td>
                            <input type="text" name="creditnames[credit3][title]"
                                   value="{{$creditnames['credit3']['title']}}" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="creditnames[credit4][status]" value="1" {{$creditnames['credit4']['status']?'checked="checked"':''}}>
                        </td>
                        <td>credit4</td>
                        <td>
                            <input type="text" name="creditnames[credit4][title]"
                                   value="{{$creditnames['credit4']['title']}}" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="creditnames[credit5][status]" value="1" {{$creditnames['credit5']['status']?'checked="checked"':''}}>
                        </td>
                        <td>credit5</td>
                        <td>
                            <input type="text" name="creditnames[credit5][title]"
                                   value="{{$creditnames['credit5']['title']}}" class="form-control">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <button type="submit" class="col-lg-1 btn btn-primary">保存</button>
    </form>
</block>
<script>
    function post(event) {
        event.preventDefault();
        require(['util'], function (util) {
            util.submit({successUrl: 'refresh'});
        })
    }
</script>