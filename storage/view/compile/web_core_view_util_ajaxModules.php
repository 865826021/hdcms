<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#module" aria-controls="home" role="tab" data-toggle="tab">模块</a></li>
    <li role="presentation"><a href="#tpl" aria-controls="profile" role="tab" data-toggle="tab">模板</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="module">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>模块名称</th>
                <th>模块标识</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ((array)$modules as $m){?>
                <tr>
                    <td><?php echo $m['title']?></td>
                    <td><?php echo $m['name']?></td>
                    <td>
                        <button class="btn btn-default" mid="<?php echo $m['mid']?>">选择</button>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="tpl">

    </div>
</div>

<script>
    $("button").click(function () {
        if($(this).hasClass('btn-default'))
        {
            $(this).attr('class','btn btn-primary');
        }
    })
</script>