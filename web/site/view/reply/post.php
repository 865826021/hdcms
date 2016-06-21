<extend file="resource/view/site"/>
<block name="content">
    <ul class="nav nav-tabs">
        <if value="!empty($_GET['rid'])">
            <li role="presentation"><a href="?s=site/reply/lists&m={{v('module.name')}}">管理{{v('module.title')}}</a></li>
            <li role="presentation"><a href="?s=site/reply/post&m={{v('module.name')}}"><i class="fa fa-plus"></i> 添加{{v('module.title')}}</a></li>
            <li role="presentation" class="active"><a href="#"><i class="fa fa-plus"></i> 编辑{{v('module.title')}}</a></li>
            <else/>
            <li role="presentation"><a href="?s=site/reply/lists&m={{v('module.name')}}">管理{{v('module.title')}}</a></li>
            <li role="presentation" class="active"><a href="#"><i class="fa fa-plus"></i> 添加{{v('module.title')}}</a></li>
        </if>
    </ul>
    <form action="" method="post" class="form-horizontal ng-cloak" role="form" id="replyForm" ng-controller="ctrl">
        <div class="panel panel-default">
            <div class="panel-heading">
                添加回复规则
            </div>
            <div class="panel-body">
                <include file="web/site/view/reply/keyword.php"/>
            </div>
        </div>

        {{$moduleForm}}
        <button class="btn btn-primary" type="submit">保存</button>
    </form>
</block>
