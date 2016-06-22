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
			<li role="presentation"><a href="http://open.hdphp.com">应用商城</a></li>
		</ul>
		<form action="" id="form" class="form-horizontal form" method="post" enctype="multipart/form-data">
			<h5 class="page-header">模块基本信息
				<small>这里来定义你自己模块的基本信息</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块名称</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" placeholder="" name="title">
					<span class="help-block">模块的名称, 由于显示在用户的模块列表中. 不要超过10个字符 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块标识</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" placeholder="" name="name">
					<span class="help-block">模块标识符, 对应模块文件夹的名称, 系统按照此标识符查找模块定义, 只能由字母数字下划线组成 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">版本</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" placeholder="" name="version">
					<span class="help-block">模块当前版本, 此版本号用于模块的版本更新 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块类型</label>

				<div class="col-sm-10 col-xs-12">
					<select name="industry" class="form-control">
						<option value="business">主要业务</option>
						<option value="customer">客户关系</option>
						<option value="marketing">营销与活动</option>
						<option value="tools">常用服务与工具</option>
						<option value="industry">行业解决方案</option>
						<option value="other">其他</option>
					</select>
					<span class="help-block">模块的类型, 用于分类展示和查找你的模块 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块简述</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" placeholder="" name="resume">
					<span class="help-block">模块功能描述, 使用简单的语言描述模块的作用, 来吸引用户 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块介绍</label>

				<div class="col-sm-10 col-xs-12">
					<textarea name="detail" cols="30" rows="3" class="form-control"></textarea>
					<span class="help-block">模块详细描述, 详细介绍模块的功能和使用方法 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">作者</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" placeholder="" name="author">
					<span class="help-block">模块的作者, 留下你的大名吧 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">发布页</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" placeholder="" name="url" value="http://open.hdcms.com">
					<span class="help-block">模块的发布页, 用于发布模块更新信息的页面 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">设置项</label>

				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox" name="setting" value="true">
						存在全局设置项
					</label>
					<span class="help-block">此模块是否存在全局的配置参数, 用于对每个模块设置独立的配置项 </span>
				</div>
			</div>
			<h5 class="page-header">桌面应用设置
				<small>这里定义桌面访问时的业务处理</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">桌面入口导航</label>

				<div class="col-sm-10">
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
						<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
							<span class="input-group-addon">操作名称</span>
							<input class="form-control" name="bindings[web][title][]" type="text" placeholder="请输入中文操作名称">
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
						<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
							<span class="input-group-addon">入口标识</span>
							<input class="form-control" name="bindings[web][do][]" type="text" placeholder="请输入操作入口">
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
						<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
							<span class="input-group-addon">操作附加数据</span>
							<input class="form-control" name="bindings[web][data][]" type="text" placeholder="操作附加数据">
						</div>
					</div>
					<span class="help-block">桌面入口文件是PC端访问的入口标识</span>
				</div>
			</div>

			<div id="bindings-member">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">桌面个人中心导航</label>

					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" name="bindings[member][title][]" type="text" placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" name="bindings[member][do][]" type="text" placeholder="请输入操作入口">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作附加数据</span>
								<input class="form-control" name="bindings[member][data][]" type="text" placeholder="操作附加数据">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div style="margin-left:-15px;">
								<label class="checkbox-inline " style="vertical-align:bottom">
									<input type="checkbox" name="bindings[member][directly][]" value="true">无需登陆直接展示
								</label>
								&nbsp; &nbsp; &nbsp;<a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()"
								                       class="fa fa-times-circle" title="删除此操作"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
				<div class="col-sm-10 col-xs-12">
					<div class="well well-sm">
						<a href="javascript:;" onclick="addFeature('member', '桌面个人中心导航');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
					</div>
                    <span class="help-block">
                        在PC端的个人中心上显示相关功能的链接入口
                    </span>
					<span class="help-block"><strong>注意:桌面个人中心导航扩展功能定义于 site 类中,方法以doWeb开启</strong></span>
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
							<input type="checkbox" name="subscribes[]" value="text">文本消息(重要)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="image">图片消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="voice">语音消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="video">视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="shortvideo">小视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="location">位置消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="link">链接消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="subscribe">粉丝开始关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="unsubscribe">粉丝取消关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="scan">扫描二维码
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="track">追踪地理位置
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="click">点击菜单(模拟关键字)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="subscribes[]" value="view">点击菜单(链接)
						</label>
					</div>
					<span class="help-block">订阅特定的消息类型后, 此消息类型的消息到达系统后将会以通知的方式(消息数据只读, 并不能返回处理结果)调用模块的接受器, 用这样的方式可以实现全局的数据统计分析等功能.</span>

					<div class="alert-warning alert">注意: 订阅的消息信息是只读的, 只能用作分析统计</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">直接处理消息</label>

				<div class="col-md-10 col-xs-12">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="text">文本消息(重要)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="image">图片消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="voice">语音消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="video">视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="shortvideo">小视频消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="location">位置消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="link">链接消息
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="subscribe">粉丝开始关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="unsubscribe">粉丝取消关注
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="scan">扫描二维码
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="track">追踪地理位置
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="click">点击菜单(模拟关键字)
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="processors[]" value="view">点击菜单(链接)
						</label>
					</div>
                    <span class="help-block">
                        当前模块能够直接处理的消息类型. 如果公众平台传递过来的消息类型不在设定的类型列表中, 那么系统将不会把此消息路由至此模块
                    </span>

					<div class="alert-warning alert">
						注意: 关键字路由只能针对文本消息有效, 文本消息最为重要. 其他类型的消息并不能被直接理解, 多数情况需要使用文本消息来进行语境分析, 再处理其他相关消息类型
					</div>
				</div>

			</div>

			<h5 class="page-header">微站功能绑定
				<small>这里来定义此功能模块中微站的相关功能如何同系统对接</small>
			</h5>
			<div id="bindings-cover">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">功能封面</label>

					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" name="bindings[cover][title][]" type="text" placeholder="请输入中文操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" name="bindings[cover][do][]" type="text" placeholder="请输入操作入口">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作附加数据</span>
								<input class="form-control" name="bindings[cover][data][]" type="text" placeholder="操作附加数据">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
							<div style="margin-left:-15px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<input type="checkbox" name="bindings[cover][directly][]" value="true">无需登陆直接展示
								</label> &nbsp; &nbsp; &nbsp;
								<a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle"
								   title="删除此操作"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<div class="well well-sm">
						<a href="javascript:;" onclick="addFeature('cover', '功能封面');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
					</div>
					<span class="help-block">功能封面是定义微站里一个独立功能的入口(手机端操作), 将呈现为一个图文消息, 点击后进入微站系统中对应的功能.</span>
					<span class="help-block"><strong>注意: 功能封面扩展功能定义于 site 类的实现中</strong></span>
				</div>
			</div>

			<!--业务功能-->
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">嵌入规则</label>

				<div class="col-sm-10 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox" name="rule" value="true">
						需要嵌入规则
					</label>
                    <span class="help-block">注意: 如果需要嵌入规则, 那么此模块必须能够处理文本类型消息, 模块安装后系统会自动添加“回复规则列表”菜单，用户可以设置关键字触发到模块中。
开发者必须要完善 processor.php 类文件中的 public function respond(){} 方法</span>
				</div>
			</div>
			<div id="bindings-rule">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">规则列表</label>

					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" name="bindings[rule][title][]" type="text" placeholder="请输入操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" name="bindings[rule][do][]" type="text" placeholder="请输入操作入口">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作附加数据</span>
								<input class="form-control" name="bindings[rule][data][]" type="text" placeholder="操作附加数据">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 ">
							<div style="margin-left:-15px;">
								<label class="checkbox-inline" style="vertical-align:bottom">
									<input type="checkbox" name="bindings[rule][directly][]" value="true">无需登陆直接展示
								</label> &nbsp; &nbsp; &nbsp;
								<a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle"
								   title="删除此操作"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<div class="well well-sm">
						<a href="javascript:;" onclick="addFeature('rule', '规则列表');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
					</div>
                    <span class="help-block">
                        规则列表是定义可重复使用或者可创建多次的活动的功能入口(管理后台Web操作), 每个活动对应一条规则. 一般呈现为图文消息, 点击后进入定义好的某次活动中.
                    </span>
					<span class="help-block"><strong>注意: 规则列表扩展功能定义于 site 类的实现中,方法以doSite开启</strong></span>
				</div>
			</div>
			<!--业务功能-->
			<div id="bindings-business">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">业务功能</label>

					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" name="bindings[business][title][]" type="text" placeholder="请输入操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" name="bindings[business][do][]" type="text" placeholder="请输入操作入口">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作附加数据</span>
								<input class="form-control" name="bindings[business][data][]" type="text" placeholder="操作附加数据">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div style="margin-left:-15px;">
								<label class="checkbox-inline " style="vertical-align:bottom">
									<input type="checkbox" name="bindings[business][directly][]" value="true">无需登陆直接展示 </label>
								&nbsp; &nbsp; &nbsp; <a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()"
								                        class="fa fa-times-circle" title="删除此操作"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">权限标识</label>

				<div class="col-xs-12 col-sm-12 col-md-10 col-lg-11">
					<textarea name="permission" cols="30" rows="6" class="form-control" placeholder="添加商品: shop_add"></textarea>
                    <span class="help-block">
                        如果您设计的模块添加的自定义的控制器需要权限设置(后台管理使用)，您可以在这里输入权限标识，
权限标识由：控制器名_方法名组成。例如,商城模块的添加商品权限标识：goods_add",说明:控制器名称为：goods,方法为：add,则对应标识为：goods_add
,多个权限标识使用换行隔开。模块方法中使用auth('goods_add')进行权限验证
                    </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<div class="well well-sm">
						<a href="javascript:;" onclick="addFeature('business', '业务功能');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
					</div>
                    <span class="help-block">
                        业务功能会在后台生成一个导航入口, 用于对模块定义的内容进行管理.
                    </span>
					<span class="help-block"><strong>注意: 业务功能菜单定义于 site 类的实现中,方法以doSite开始</strong></span>
				</div>
			</div>
			<!--微站首页导航-->
			<div id="bindings-home">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">微站首页导航</label>

					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" name="bindings[home][title][]" type="text" placeholder="请输入操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" name="bindings[home][do][]" type="text" placeholder="请输入操作入口">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作附加数据</span>
								<input class="form-control" name="bindings[home][data][]" type="text" placeholder="操作附加数据">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div style="margin-left:-15px;">
								<label class="checkbox-inline " style="vertical-align:bottom">
									<input type="checkbox" name="bindings[home][directly][]" value="true">无需登陆直接展示 </label>
								&nbsp; &nbsp; &nbsp; <a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()"
								                        class="fa fa-times-circle" title="删除此操作"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<div class="well well-sm">
						<a href="javascript:;" onclick="addFeature('home', '微站首页导航');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
					</div>
                    <span class="help-block">
                        在微站的首页上显示相关功能的链接入口(手机端操作), 一般用于通用功能的展示.
                    </span>
					<span class="help-block"><strong>注意: 微站首页导航扩展功能定义于 site 类的实现中</strong></span>
				</div>
			</div>
			<!--微站个人中心导航-->
			<div id="bindings-profile">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">微站个人中心导航</label>

					<div class="col-sm-10">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作名称</span>
								<input class="form-control" name="bindings[profile][title][]" type="text" placeholder="请输入操作名称">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">入口标识</span>
								<input class="form-control" name="bindings[profile][do][]" type="text" placeholder="请输入操作入口">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="input-group" style="margin-left:-15px;margin-bottom:10px">
								<span class="input-group-addon">操作附加数据</span>
								<input class="form-control" name="bindings[profile][data][]" type="text" placeholder="操作附加数据">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div style="margin-left:-15px;">
								<label class="checkbox-inline " style="vertical-align:bottom">
									<input type="checkbox" name="bindings[profile][directly][]" value="true">无需登陆直接展示
								</label> &nbsp; &nbsp; &nbsp;
								<a href="javascript:;" onclick="$(this).parents('.form-group').eq(0).remove()" class="fa fa-times-circle"
								   title="删除此操作"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<div class="well well-sm">
						<a href="javascript:;" onclick="addFeature('profile', '微站个人中心导航');">添加操作 <i class="fa fa-plus-circle" title="添加菜单"></i></a>
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
						<input name="versionCode[]" type="checkbox" value="2.0">hdcms 2.0</label>
					<span class="help-block">当前模块兼容的系统版本, 安装时会判断版本信息, 不兼容的版本将无法安装</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块缩略图</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="text" class="form-control" name="thumb" readonly="" value="">
						<div class="input-group-btn">
							<button onclick="upImage(this)" class="btn btn-default" type="button">选择图片</button>
						</div>
					</div>
					<div class="input-group" style="margin-top:5px;">
						<img src="resource/images/nopic.jpg" class="img-responsive img-thumbnail img-thumb" width="150">
						<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="removeImg(this)">×</em>
					</div>
					<span class="help-block">用 48*48 的图片来让你的模块更吸引眼球吧</span>
				</div>
				<script>
					//上传图片
					function upImage(obj) {
						require(['util'], function (util) {
							util.image(function (images) { 
								$("[name='thumb']").val(images[0]);
								$(".img-thumb").attr('src', images[0]);
							})
						});
					}
					//移除图片 
					function removeImg(obj) {
						$(obj).prev('img').attr('src', 'resource/images/nopic.jpg');
						$(obj).parent().prev().find('input').val('');
					}
				</script>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模块封面</label>

				<div class="col-sm-10">
					<div class="input-group">
						<input type="text" class="form-control" name="cover" readonly="" value="">
						<div class="input-group-btn">
							<button onclick="upCoverImage(this)" class="btn btn-default" type="button">选择图片</button>
						</div>
					</div>
					<div class="input-group" style="margin-top:5px;">
						<img src="resource/images/nopic.jpg" class="img-responsive img-thumbnail img-cover" width="150">
						<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="removeImg(this)">×</em>
					</div>
					<span class="help-block">模块封面, 大小为 600*350, 更好的设计将会获得官方推荐位置</span>
				</div>
				<script>
					//上传图片
					function upCoverImage(obj) {
						require(['util'], function (util) {
							util.image(function (images) {
								$("[name='cover']").val(images[0]);
								$(".img-cover").attr('src', images[0]);
							})
						});
					}

				</script>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块安装脚本</label>

				<div class="col-sm-10 col-xs-12">
					<textarea class="form-control" name="install" rows="4"></textarea>
					<span class="help-block">当前模块全新安装时所执行的脚本, 可以定义为SQL语句. 也可以指定为单个的php脚本文件, 如: install.php</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块卸载脚本</label>

				<div class="col-sm-10 col-xs-12">
					<textarea class="form-control" name="uninstall" rows="4"></textarea>
					<span class="help-block">当前模块卸载时所执行的脚本, 可以定义为SQL语句. 也可以指定为单个的php脚本文件, 如: uninstall.php</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">模块升级脚本</label>

				<div class="col-sm-10 col-xs-12">
					<textarea class="form-control" name="upgrade" rows="4"></textarea>
					<span class="help-block">当前模块更新时所执行的脚本, 可以定义为SQL语句. 也可以指定为单个的php脚本文件, 如: upgrade.php. (推荐使用php脚本, 方便检测字段及兼容性)</span>
					<input type="hidden" name="token" value="6708fa25">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<input name="method" type="hidden" value="download">
					<input name="token" type="hidden" value="6708fa25">
					<input type="submit" class="btn btn-primary" id="createBtn" name="submit" onclick="$(':hidden[name=method]').val('create');"
					       value="生成模块模板">
					<p class="help-block">点此直接在源码目录 addons/<span class="identifie"></span> 处生成模块开发的模板文件, 方便快速开发</p>
				</div>
			</div>
		</form>
	</div>
</block>

<script>
	function addFeature(type, title) {
		var html = '<div class="form-group">\
            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">' + title + '</label>\
            <div class="col-sm-10">\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">\
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">\
            <span class="input-group-addon">操作名称</span>\
            <input class="form-control" name="bindings[' + type + '][title][]" type="text" placeholder="请输入中文操作名称">\
            </div>\
            </div>\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">\
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">\
            <span class="input-group-addon">入口标识</span>\
            <input class="form-control" name="bindings[' + type + '][do][]" type="text" placeholder="请输入操作入口">\
            </div>\
            </div>\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">\
            <div class="input-group" style="margin-left:-15px;margin-bottom:10px">\
            <span class="input-group-addon">操作附加数据</span>\
            <input class="form-control" name="bindings[' + type + '][data][]" type="text" placeholder="操作附加数据">\
            </div>\
            </div>\
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">\
                <div style="margin-left:-15px;">\
                    <label class="checkbox-inline " style="vertical-align:bottom">\
                        <input type="checkbox" name="bindings[' + type + '][directly][]" value="true">无需登陆直接展示\
                    </label> &nbsp; &nbsp; &nbsp;\
                     <a href="javascript:;" onclick="$(this).parents(\'.form-group\').eq(0).remove()" class="fa fa-times-circle" title="删除此操作"></a>\
                </div>\
            </div>\
            </div>\
            </div>';
		$('#bindings-' + type).append(html);
	}


	$("form").submit(function () {
		//验证表单
		var msg = '';
		var title = $.trim($(':text[name="title"]').val());
		if (title == '') {
			msg += '模块名称不能为空 <br/>';
		}
		var names = $.trim($(':text[name="name"]').val());
		if (!/^[a-z]\w+$/i.test(names)) {
			msg += '模块标识必须以英文字母开始. 后跟英文,字母,数字或下划线<br/>';
		}
		var version = $.trim($(':text[name="version"]').val());
		if (!/^[\d\.]+$/i.test(version)) {
			msg += '请设置版本号. 版本号只能为数字或小数点<br/>';
		}
		var resume = $.trim($(':text[name="resume"]').val());
		if (resume == '') {
			msg += '模块简述不能为空<br/>';
		}
		var detail = $.trim($('[name="detail"]').val());
		if (detail == '') {
			msg += '请输入详细介绍内容<br/>';
		}
		var author = $.trim($(':text[name="author"]').val());
		if (author == '') {
			msg += '作者不能为空<br/>';
		}
		var url = $.trim($(':text[name="url"]').val());
		if (url == '') {
			msg += '请输入发布url<br/>';
		}
		var versionCode = $.trim($(':checkbox[name="versionCode[]"]:checked').val());
		if (versionCode == '') {
			msg += '请选择兼容版本<br/>';
		}
		var thumb = $.trim($(':text[name="thumb"]').val());
		if (thumb == '') {
			msg += '模块缩略图不能为空<br/>';
		}
		var thumb = $.trim($(':text[name="cover"]').val());
		if (thumb == '') {
			msg += '模块封面不能为空<br/>';
		}
		if (msg) {
			util.message(msg, '', 'error');
			return false;
		}
	});
</script>