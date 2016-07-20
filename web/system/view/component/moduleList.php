<table class="table table-hover">
    <thead>
    <tr>
        <th>选择</th>
        <th>模块名称</th>
        <th>标识</th>
    </tr>
    </thead>
    <tbody>
    <foreach from="$modules" value="$m">
        <tr>
            <td>
                <input type="checkbox" name="nid[]" value="{{$m['mid']}}" title="{{$m['title']}}" module_name="{{$m['name']}}">
            </td>
            <td>
                {{$m['title']}}
            </td>
            <td>
                <span class="label label-info">{{$m['name']}}</span>
            </td>
        </tr>
    </foreach>
    </tbody>
</table>