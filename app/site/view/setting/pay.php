<extend file="resource/view/site"/>
<block name="content">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#">支付参数</a></li>
    </ul>
    <form action="" method="post" class="form-horizontal ng-cloak" enctype="multipart/form-data" id="form" ng-controller="ctrl" ng-submit="submit($event)">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading">
                设置微信支付开关
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">微信支付</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" name="pay[wechat][open]" ng-model="pay.wechat.open" value="1"> 开启
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="pay[wechat][open]" ng-model="pay.wechat.open" value="0"> 关闭
                        </label>
                        <span class="help-block">是否使用微信支付</span>
                    </div>
                </div>
                <div ng-show="pay.wechat.open==1">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">接口类型</label>
                        <div class="col-sm-10">
                            <label class="radio-inline" ng-init="version=1">
                                <input type="radio" name="pay[wechat][version]" ng-model="version" value="0"> 旧版
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="pay[wechat][version]" ng-model="version" value="1"> 新版(2014年9月之后申请的)
                            </label>
                            <span class="help-block">由于微信支付接口调整，需要根据申请时间来区分支付接口</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">支付账号</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">后盾-{{\Wx::chatNameBylevel($wechat['level'])}}</p>
                            <if value="$wechat['level']!=4">
                                <strong class="text-danger">微信支付要求公众号为“认证服务号”，该公众号没有微信支付的权限</strong>
                            </if>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">身份标识<br/>(appId)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" readonly="readonly" value="{{$wechat['appid']}}">
                            <span class="help-block">公众号身份标识 请通过修改公众号信息来保存</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">身份密钥<br/>(appSecret)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" readonly="readonly" value="{{$wechat['appsecret']}}">
                            <span class="help-block">公众平台API(参考文档API 接口部分)的权限获取所需密钥Key 请通过修改公众号信息来保存</span>
                        </div>
                    </div>
                    <!--新版start-->
                    <div class="form-group" ng-show="version==1">
                        <label class="col-sm-2 control-label">微信支付商户号<br/>(MchId)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pay[wechat][mch_id]" ng-model="pay.wechat.mch_id">
                            <span class="help-block">公众号支付请求中用于加密的密钥Key</span>
                        </div>
                    </div>
                    <div class="form-group" ng-show="version==1">
                        <label class="col-sm-2 control-label">商户支付密钥<br/>(API密钥)</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pay[wechat][key]" ng-model="pay.wechat.key">
                                <span class="input-group-addon" style="cursor: pointer" ng-click="createWeichatApi()">生成新的</span>
                            </div>
                            <span class="help-block">此值需要手动在腾讯商户后台API密钥保持一致。查看设置教程</span>
                        </div>
                    </div>
                    <!--新版end-->
                    <!--旧版start-->
                    <div class="form-group" ng-show="version==0">
                        <label class="col-sm-2 control-label">商户身份<br/>(partnerId)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pay[wechat][partnerid]" ng-model="pay.wechat.partnerid">
                            <span class="help-block">财付通商户身份标识</span>
                            <span class="help-block">公众号支付请求中用于加密的密钥Key</span>
                        </div>
                    </div>
                    <div class="form-group" ng-show="version==0">
                        <label class="col-sm-2 control-label">商户密钥<br/>(partnerKey)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pay[wechat][partnerkey]" ng-model="pay.wechat.partnerkey">
                            <span class="help-block">财付通商户权限密钥Key</span>
                        </div>
                    </div>
                    <div class="form-group" ng-show="version==0">
                        <label class="col-sm-2 control-label">通信密钥<br/>(paySignKey)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pay[wechat][paysignkey]" ng-model="pay.wechat.paysignkey">
                            <span class="help-block">公众号支付请求中用于加密的密钥Key</span>
                        </div>
                    </div>
                    <!--旧版start-->
                    <!--证书-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">证书格式<br/>(apiclient_cert.pem)</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pay[wechat][apiclient_cert]" readonly="" ng-model="pay.wechat.apiclient_cert">
                                <div class="input-group-btn">
                                    <button onclick="upFile('pay[wechat][apiclient_cert]')" class="btn btn-default" type="button">选择证书文件</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">证书密钥格式<br/>(apiclient_key.pem)</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pay[wechat][apiclient_key]" readonly="" ng-model="pay.wechat.apiclient_key">
                                <div class="input-group-btn">
                                    <button onclick="upFile('pay[wechat][apiclient_key]')" class="btn btn-default" type="button">选择证书文件</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">CA证书<br/>(rootca.pem)</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="pay[wechat][rootca]" readonly="" ng-model="pay.wechat.rootca">
                                <div class="input-group-btn">
                                    <button onclick="upFile('pay[wechat][rootca]')" class="btn btn-default" type="button">选择证书文件</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="col-lg-1 btn btn-primary">保存</button>
    </form>
</block>

<script>
    //上传图片
    function upFile(names) {
        require(['util'], function (util) {
            options = {
                extensions: 'pem',
                multiple: false,//上传多文件
            };
            util.file(function (files) {
                //上传成功的文件，数组类型 
                $("[name='"+names+"']").val(files[0]);
            }, options)
        });
    }
    require(['angular', 'util', 'md5'], function (angular, util, md5) {
        angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
            $scope.pay = <?php echo json_encode(v('site.setting.pay'));?>;
            $scope.createWeichatApi = function () {
                $scope.pay.wechat.key = md5(Math.random());
            }
            $scope.submit=function(event){
                event.preventDefault();
                util.submit();
            }
        }]);
        angular.bootstrap($("#form")[0], ['app']);
    });
</script>







