<extend file="resource/view/site"/>
<block name="content">
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#">平台相关数据</a></li>
    </ul>
    <div class="page-header">
        <h4><i class="fa fa-comments"></i> 公众号信息</h4>
    </div>
    <div class="panel panel-default row">
        <div class="panel-body">
            <div class="col-sm-7">
                <p>
                    <strong>{{v("wechat.wename")}}</strong>
                    <span class="label label-success">
                         <?php echo m('Wechat')->chatNameBylevel(v('wechat.level')) ?>
                    </span>
                    &nbsp;&nbsp;&nbsp;
                    <if value="v('wechat.is_connect')">
                       <span class="text-success">
                            <i class="fa fa-check-circle"></i> 接入成功
                       </span>
                    </if>
                </p>
                <p>
                    <strong>接口地址:</strong>
                    <span>http://hdcms.jiunixing.com/index.php?s=site/api/deal&siteid={{v("site.siteid")}}</span>
                </p>
                <p>
                    <strong>Token:</strong>
                    <span>{{v('wechat.token')}}</span>
                </p>
            </div>
        </div>
    </div>
</block>