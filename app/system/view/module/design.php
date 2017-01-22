<extend file="resource/view/system"/>
<block name="content">
	<div class="clearfix">
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i></li>
			<li><a href="?s=system/manage/menu">系统</a></li>
			<li class="active">设置新模块</li>
		</ol>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="{{u('installed')}}">已经安装模块</a></li>
			<li role="presentation"><a href="?s=system/module/prepared">安装模块</a></li>
			<li role="presentation" class="active"><a href="?s=system/module/design">设计新模块</a></li>
			<li role="presentation"><a href="{{c('api.cloud')}}?a=site/store&t=web&siteid=1&m=store" target="_blank">应用商城</a>
			</li>
		</ul>
		<form action="" class="form-horizontal form ng-cloak" ng-controller="ctrl" method="post">
			{{csrf_field()}}
			<h5 class="page-header">模块基本信息
				<small>这里来定义你自己模块的基本信息</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块名称</label>
				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.title">
					<span class="help-block">模块的名称, 由于显示在用户的模块列表中, 请输入中文发便记忆并有吸引力的名称, 不要超过10个字符 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块标识</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.name">
					<span class="help-block">模块标识符, 对应模块文件夹的名称, 系统按照此标识符查找模块定义, 只能由英文字母组成 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块版本</label>
				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.version">
					<span class="help-block">模块当前版本, 方便使用者识别此模块。此版本号用于模块的版本更新 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块类型</label>
				<div class="col-sm-10 col-xs-12">
					<select ng-model="field.industry" class="form-control"
					        ng-options="c.name as c.title for c in industry"></select>
					<span class="help-block">模块的类型, 用于分类展示和查找你的模块 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块简述</label>
				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.resume">
					<span class="help-block">模块功能描述, 使用简单的语言描述模块的作用, 来吸引用户 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块介绍</label>
				<div class="col-sm-10 col-xs-12">
					<textarea ng-model="field.detail" cols="30" rows="3" class="form-control"></textarea>
					<span class="help-block">模块详细描述, 详细介绍模块的功能和使用方法 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块作者</label>
				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.author">
					<span class="help-block">模块的作者, 留下你的大名吧 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">发布网址</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" ng-model="field.url">
					<span class="help-block">模块的发布页, 用于发布模块更新信息的页面 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块配置</label>
				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox" value="true" ng-model="field.setting">
						存在全局设置项
					</label>
					<span class="help-block">此模块是否存在配置参数, 系统会对每个模块设置独立的配置项模块间互不影响</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模板标签</label>
				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox" value="true" ng-model="field.tag">
						设置模板标签
					</label>
					<span class="help-block">用于创建模板使用的自定义标签, 模板标签在 system/tag.php 文件中实现</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">中间件</label>
				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox" value="true" ng-model="field.middleware">
						设置中间件
					</label>
					<span class="help-block">用于管理站点中间件中的模块动作</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">路由规则</label>
				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox" value="true" ng-model="field.router">
						设置路由规则
					</label>
					<span class="help-block">用于管理模块链接的路由器设置</span>
				</div>
			</div>
			<h5 class="page-header">公众平台消息处理选项
				<small>这里来定义公众平台消息相关处理</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">订阅消息类型</label>

				<div class="col-md-10 col-xs-12">
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.text" value="text">文本消息(重要)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.image" value="image">图片消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.voice" value="voice">语音消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.video" value="video">视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.shortvideo" value="shortvideo">小视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.location" value="location">位置消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.link" value="link">链接消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.subscribe" value="subscribe">粉丝开始关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.unsubscribe" value="unsubscribe">粉丝取消关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.scan" value="scan">扫描二维码
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.track" value="track">追踪地理位置
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.click" value="click">点击菜单(模拟关键字)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.subscribes.view" value="view">点击菜单(链接)
						</label>
					</div>
					<span class="help-block">
						订阅特定的消息类型后, 此消息类型的消息到达系统后将会以通知的方式(消息数据只读,
						并不能返回处理结果)调用模块的接受器, 用这样的方式可以实现全局的数据统计分析等功能
					</span>
					<span class="help-block"><strong>注意: 订阅消息在 system/subscribe.php 文件中实现</strong></span>
					<div class="alert-warning alert">注意: 订阅的消息信息是只读的, 只能用作分析统计</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">直接处理消息</label>
				<div class="col-md-10 col-xs-12">
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.text" value="text">文本消息(重要)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.image" value="image">图片消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.voice" value="voice">语音消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.video" value="video">视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.shortvideo" value="shortvideo">小视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.location" value="location">位置消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.link" value="link">链接消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.subscribe" value="subscribe">粉丝开始关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.unsubscribe" value="unsubscribe">粉丝取消关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.scan" value="scan">扫描二维码
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.track" value="track">追踪地理位置
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.click" value="click">点击菜单(模拟关键字)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" ng-model="field.processors.view" value="view">点击菜单(链接)
						</label>
					</div>
					<span class="help-block">
                        当前模块能够直接处理的消息类型. 如果公众平台传递过来的消息类型不在设定的类型列表中, 那么系统将不会把此消息路由至此模块
                    </span>
					<span class="help-block"><strong>注意: 直接处理消息在 system/processor.php 文件中实现</strong></span>
					<div class="alert-warning alert">
						注意: 关键字路由只能针对文本消息有效, 文本消息最为重要. 其他类型的消息并不能被直接理解, 多数情况需要使用文本消息来进行语境分析, 再处理其他相关消息类型
					</div>
				</div>
			</div>
			<h5 class="page-header">微信回复设置
				<small>微信公众号回复内容设置</small>
			</h5>
			<div ng-repeat="v in field.cover">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">功能封面</label>
					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" ng-model="v.title" type="text" placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" ng-model="v.do" type="text" placeholder="请输入操作入口">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-1">
							<div style="margin-left:-45px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<a href="javascript:;" ng-click="delCover(v)" class="fa fa-times-circle"
									   title="删除此操作"></a>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-xs-12 col-md-9 col-md-offset-1">
					<div class="well well-sm">
						<a href="javascript:;" ng-click="addCover()">
							添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i>
						</a>
					</div>
					<span class="help-block">
						功能封面是定义微站里一个独立功能的入口(微信客户端操作), 将呈现为一个图文消息,
						点击后进入微站系统中对应的功能,
					</span>
					<span class="help-block"><strong>注意: 功能封面在 system/cover.php 文件中实现</strong></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">回复规则</label>

				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox" ng-model="field.rule"> 需要回复规则
					</label>
					<span class="help-block">
						注意: 如果需要回复规则, 那么模块必须能够处理文本类型消息,
						模块安装后系统会自动添加 “回复规则列表” 菜单，用户可以设置关键字触发到模块中。
					</span>
				</div>
			</div>

			<!--业务功能-->
			<h5 class="page-header">模块业务设置
				<small>设置模块的业务功能菜单, 业务功能的操作名称不能使用
				<strong class="label label-danger">Navigate</strong> , 因为系统已经用于管理菜单业务。
				</small>
			</h5>
			<div ng-repeat="(key,v) in field.business"
			     style="padding-top: 20px;margin-bottom: 10px;background-color: #efefef;border:solid 1px #dedede;">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">业务功能</label>
					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" ng-model="v.title" type="text"
								       placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" ng-model="v.controller" type="text"
								       placeholder="请输入控制器文件名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-1">
							<div style="margin-left:-45px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<a href="javascript:;" ng-click="delBusiness(v)" class="fa fa-times-circle"
									   title="删除此操作"></a>
								</label>
							</div>
						</div>
					</div>
				</div>
				<!--动作方法-->
				<div class="form-group" ng-repeat="d in v.action">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">动作方法</label>
					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" ng-model="d.title" type="text"
								       placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" ng-model="d.do" type="text" placeholder="请输入操作入口">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-1">
							<div style="margin-left:-45px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<a href="javascript:;" ng-click="delBusinessAction(key,d)"
									   class="fa fa-times-circle"
									   title="删除此操作"></a>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-10 col-xs-12 col-md-9 col-md-offset-1">
						<div class="well well-sm">
							<a href="javascript:;" ng-click="addBusinessAction(v)">
								添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i>
							</a>
						</div>
					</div>
				</div>
				<!--动作方法end-->
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-xs-12 col-md-12">
					<div class="well well-sm">
						<a href="javascript:;" ng-click="addBusiness()">
							添加业务 <i class="fa fa-plus-circle" title="添加菜单"></i>
						</a>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">权限标识</label>
				<div class="col-xs-12 col-sm-12 col-md-10">
					<textarea cols="30" rows="6" class="form-control" ng-model="field.permissions"
					          placeholder="添加商品:shop_add"></textarea>
					<span class="help-block col-md-11">
                        如果您设计的模块添加的业务需要权限设置(后台管理使用)，您可以在这里输入权限标识，
权限标识由：控制器名_方法名组成。例如,商城模块的添加商品权限标识：goods_add",说明:控制器名称为：goods,方法为：add,则对应标识为：goods_add
,多个权限标识使用换行隔开。模块方法中使用auth('goods_add')进行权限验证
                    </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
				<div class="col-sm-10 col-xs-12">
					<div class="well well-sm">
						<a href="javascript:;">
							添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i>
						</a>
					</div>
					<span class="help-block">
                        业务功能会在后台生成一个导航入口, 用于对模块定义的内容进行管理.
                    </span>
					<span class="help-block"><strong>注意: 业务功能菜单定义于 site 类的实现中,方法以doSite开始</strong></span>
				</div>
			</div>

			<!--模块入口设置-->
			<h5 class="page-header">模块入口设置
				<small>模块的入口导航设置</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">入口导航</label>
				<div class="col-sm-10">
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
							<span class="input-group-addon">操作名称</span>
							<input class="form-control" ng-model="field.web.entry.title" type="text"
							       placeholder="请输入中文操作名称">
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-5">
						<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
							<span class="input-group-addon">入口标识</span>
							<input class="form-control" ng-model="field.web.entry.do" type="text" placeholder="请输入操作入口">
						</div>
					</div>
					<span class="help-block" style="clear: both;">
						在站点设置中设置模块为默认模块时, 通过站点域名进入后默认执行的动作。
					</span>
					<div class="alert alert-warning col-md-11">
						只有在站点设置中将模块定义为默认模块时本功能才有意义
					</div>
				</div>
			</div>
			<h5 class="page-header">桌面导航设置
				<small>这里定义桌面访问时的导航菜单</small>
			</h5>
			<div ng-repeat="v in field.web.member">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">会员中心</label>
					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" ng-model="v.title" type="text" placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" ng-model="v.do" type="text" placeholder="请输入操作入口">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-1">
							<div style="margin-left:-45px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<a href="javascript:;" ng-click="delWebMember(v)" class="fa fa-times-circle"
									   title="删除此操作"></a>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-xs-12 col-md-9 col-md-offset-1">
					<div class="well well-sm">
						<a href="javascript:;" ng-click="addWebMember()">
							添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i>
						</a>
					</div>
					<span class="help-block">在PC桌面端的会员中心上显示相关功能的链接入口</span>
					<span class="help-block"><strong>注意: 桌面个人中心导航在 system/navigate.php 文件中实现</strong></span>
				</div>
			</div>
			<!--移动端首页导航-->
			<h5 class="page-header">移动端导航设置
				<small>移动端的导航菜单设置</small>
			</h5>
			<div ng-repeat="v in field.mobile.home">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">首页导航</label>
					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" ng-model="v.title" type="text" placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" ng-model="v.name" type="text" placeholder="请输入操作入口">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-1">
							<div style="margin-left:-45px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<a href="javascript:;" ng-click="delMobileHome(v)" class="fa fa-times-circle"
									   title="删除此操作"></a>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-xs-12 col-md-9 col-md-offset-1">
					<div class="well well-sm">
						<a href="javascript:;" ng-click="addMobileHome()">
							添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i>
						</a>
					</div>
					<span class="help-block">
                        在微站的首页上显示相关功能的链接入口(手机端操作), 一般用于通用功能的展示.
                    </span>
					<span class="help-block"><strong>注意: 微站首页导航扩展功能定义于 site 类的实现中</strong></span>
				</div>
			</div>
			<!--微站会员中心导航-->
			<div ng-repeat="v in field.mobile.member">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">会员中心</label>
					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-6">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" ng-model="v.title" type="text" placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" ng-model="v.do" type="text" placeholder="请输入操作入口">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-1">
							<div style="margin-left:-45px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<a href="javascript:;" ng-click="delMobileMember(v)" class="fa fa-times-circle"
									   title="删除此操作"></a>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-xs-12 col-md-9 col-md-offset-1">
					<div class="well well-sm">
						<a href="javascript:;" ng-click="addMobileMember()">
							添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i>
						</a>
					</div>
					<span class="help-block">
                        在微站的个人中心上显示相关功能的链接入口(手机端操作), 一般用于个人信息, 或针对个人的数据的展示.
                    </span>
					<span class="help-block"><strong>注意: 微站个人中心导航扩展功能定义于 site 类的实现中</strong></span>
				</div>
			</div>

			<h5 class="page-header">模块发布
				<small>这里来定义模块发布时需要的配置项</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">兼容的版本</label>
				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input ng-model="field.compatible_version.version2" type="checkbox">hdcms 2.0</label>
					<span class="help-block">当前模块兼容的系统版本, 安装时会判断版本信息, 不兼容的版本将无法安装</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块缩略图</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="text" class="form-control" readonly="" ng-model="field.thumb">
						<div class="input-group-btn">
							<button ng-click="uploadThumb()" class="btn btn-default" type="button">选择图片</button>
						</div>
					</div>
					<div class="input-group" style="margin-top:5px;">
						<img ng-src="@{{field.thumb}}" class="img-responsive img-thumbnail img-thumb" width="150">
						<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片"
						    ng-click="field.thumb='resource/images/nopic.jpg'">×</em>
					</div>
					<span class="help-block">用 80*80 的图片来让你的模块更吸引眼球吧</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">官网展示图</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="text" class="form-control" readonly="" ng-model="field.preview">
						<div class="input-group-btn">
							<button ng-click="uploadPreview()" class="btn btn-default" type="button">选择图片</button>
						</div>
					</div>
					<div class="input-group" style="margin-top:5px;">
						<img ng-src="@{{field.preview}}" class="img-responsive img-thumbnail img-cover" width="150">
						<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片"
						    ng-click="field.preview='resource/images/nopic.jpg'">×</em>
					</div>
					<span class="help-block">模块封面, 大小为 600*350, 更好的设计将会获得官方推荐位置</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
				<div class="col-sm-10 col-xs-12">
					<input type="submit" class="btn btn-primary" value="点击生成模块文件结构">
					<p class="help-block">点此直接在源码目录 addons/<span class="identifie"></span> 处生成模块开发的模板文件, 方便快速开发</p>
				</div>
			</div>
			<textarea name="data" ng-model="data" hidden="hidden"></textarea>
		</form>
	</div>
</block>

<script>
	require(['angular', 'util', 'underscore', 'jquery'], function (angular, util, _, $) {
		$(function () {
			angular.module('app', []).controller('ctrl', ['$scope', function ($scope) {
				$scope.industry = [
					{id: 1, title: '主要业务', name: 'business'},
					{id: 1, title: '客户关系', name: 'customer'},
					{id: 1, title: '营销与活动', name: 'marketing'},
					{id: 1, title: '常用服务与工具', name: 'tools'},
					{id: 1, title: '行业解决方案', name: 'industry'},
					{id: 1, title: '其他', name: 'other'}
				];
				$scope.field = {
					"manifest_code": "2.0",
					"title": "aaa",
					"name": "hdcms",
					"version": "1.0",
					"industry": "business",
					"resume": "resume",
					"detail": "detail",
					"author": "author",
					"url": "url",
					"setting": true,
					"tag": true,
					"middleware":true,
					"router":true,
					"web": {
						"entry": {
							"title": "桌面入口导航",
							"do": "zuomianrukou",
						},
						"member": [
							{
								"title": "桌面会员中心",
								"do": "zuomianmember",
							}
						]
					},
					"mobile": {
						"home": [
							{
								"title": "移动端首页导航",
								"do": "mobilesouye",
							}
						],
						"member": [
							{
								"title": "移动端会员中心",
								"do": "mobilemember",
							}
						]
					},
					"subscribes": {
						"text": true,
						"image": true,
						"voice": true,
						"video": true,
						"shortvideo": true,
						"location": true,
						"link": true,
						"subscribe": true,
						"unsubscribe": true,
						"scan": true,
						"track": true,
						"click": true,
						"view": true
					},
					"processors": {
						"text": true,
						"image": true,
						"voice": true,
						"video": true,
						"shortvideo": true,
						"location": true,
						"link": true,
						"subscribe": true,
						"unsubscribe": true,
						"scan": true,
						"track": true,
						"click": true,
						"view": true
					},
					"cover": [
						{
							"title": "功能封面1",
							"do": "fengmian1",
						},
						{
							"title": "功能封面2",
							"do": "fengmian2",
						}
					],
					"rule": true,
					"business": [
						{
							"title": "业务功能",
							"controller": "business",
							"action": [
								{
									"title": "控制器动作",
									"do": "action"
								}
							]
						}
					],
					"permissions": "",
					"compatible_version": {
						"version2": true
					},
					"thumb": "resource/images/nopic.jpg",
					"preview": "resource/images/nopic.jpg",
					"install": "",
					"uninstall": "",
					"upgrade": ""
				};
				$scope.uploadThumb = function () {
					util.image(function (images) {
						$scope.field.thumb = images[0];
						$scope.$apply();
					})
				}

				$scope.uploadPreview = function () {
					util.image(function (images) {
						$scope.field.preview = images[0];
						$scope.$apply();
					})
				}

				//封面导航
				$scope.addCover = function () {
					$scope.field.cover.push({"title": "", "do": ""});
				}
				$scope.delCover = function (item) {
					$scope.field.cover = _.without($scope.field.cover, item);
				}
				//桌面会员导航
				$scope.delWebMember = function (item) {
					$scope.field.web.member = _.without($scope.field.web.member, item);
				}
				$scope.addWebMember = function () {
					$scope.field.web.member.push({"title": "", "do": ""})
				}
				//移动端主页导航
				$scope.addMobileHome = function () {
					$scope.field.mobile.home.push({"title": "", "do": ""})
				}
				$scope.delMobileHome = function (item) {
					$scope.field.mobile.home = _.without($scope.field.mobile.home, item);
				}
				//移动端会员中心导航
				$scope.addMobileMember = function () {
					$scope.field.mobile.member.push({"title": "", "do": ""})
				}
				$scope.delMobileMember = function (item) {
					$scope.field.mobile.member = _.without($scope.field.mobile.member, item);
				}
				//业务管理
				$scope.addBusiness = function () {
					var business = {
						"title": "",
						"controller": "",
						"action": [{
							"title": "",
							"do": "",
							"directly": false
						}]
					}
					$scope.field.business.push(business);
				}
				$scope.delBusiness = function (item) {
					$scope.field.business = _.without($scope.field.business, item);
				}
				//添加业务动作
				$scope.addBusinessAction = function (item) {
					item.action.push({"title": "", "do": "", "directly": false});
				}
				$scope.delBusinessAction = function (key, item) {
					$scope.field.business[key].action = _.without($scope.field.business[key].action, item);
				}
				$("form").submit(function () {
					var msg = ''
					if ($scope.field.title == '') {
						msg += '模块名称不能为空<br/>';
					}
					if ($scope.field.name == '') {
						msg += '模块标识不能为空<br/>';
					}
					if ($scope.field.resume == '') {
						msg += '模块简述不能为空<br/>';
					}
					if ($scope.field.detail == '') {
						msg += '模块介绍不能为空<br/>';
					}
					if ($scope.field.author == '') {
						msg += '模块作者不能为空<br/>';
					}
					if ($scope.field.url == '') {
						msg += '发布网址不能为空<br/>';
					}
					if ($scope.field.thumb == '') {
						msg += '模块缩略图不能为空<br/>';
					}
					if ($scope.field.cover == '') {
						msg += '模块封面图不能为空<br/>';
					}

					if (msg != '') {
						util.message(msg, '', 'warning');
						return false;
					}
					$scope.data = angular.toJson($scope.field);
					$scope.$apply();
				})
			}])

			angular.bootstrap(document.body, ['app']);
		})

	})
</script>