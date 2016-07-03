<extend file="resource/view/system"/>
<block name="content">
	<div class="clearfix">
		<ol class="breadcrumb">
			<li><i class="fa fa-home"></i></li>
			<li><a href="?s=system/manage/menu">系统</a></li>
			<li class="active">设置新模板</li>
		</ol>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="{{u('installed')}}">已经安装模板</a></li>
			<li role="presentation"><a href="?s=system/template/prepared">安装模板</a></li>
			<li role="presentation" class="active"><a href="javascript:;">设计新模板</a></li>
			<li role="presentation"><a href="http://open.hdphp.com">应用商城</a></li>
		</ul>
		<form action="" id="form" class="form-horizontal form" method="post" enctype="multipart/form-data">
			<h5 class="page-header">模板基本信息
				<small>这里来定义你自己模板的基本信息</small>
			</h5>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板名称</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" name="title">
					<span class="help-block">模板的名称, 由于显示在用户的模板列表中. 不要超过10个字符 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板标识</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" name="name">
					<span class="help-block">模板标识符, 对应模板文件夹的名称, 系统按照此标识符查找模板定义, 只能由字母数字下划线组成 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">版本</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" name="version">
					<span class="help-block">模板当前版本, 此版本号用于模板的版本更新 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板类型</label>
				<div class="col-sm-10 col-xs-12">
					<select name="type" class="form-control">
						<option value="business">主要业务</option>
						<option value="customer">客户关系</option>
						<option value="marketing">营销与活动</option>
						<option value="tools">常用服务与工具</option>
						<option value="industry">行业解决方案</option>
						<option value="other">其他</option>
					</select>
					<span class="help-block">模板的类型, 用于分类展示和查找你的模板 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板简述</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" name="description">
					<span class="help-block">模板功能描述, 使用简单的语言描述模板, 来吸引用户 </span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">作者</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" name="author">
					<span class="help-block">模板的作者, 留下你的大名吧 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">发布页</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" name="url" value="http://open.hdcms.com">
					<span class="help-block">模板的发布页, 用于发布模板更新信息的页面 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">微站导航菜单数量</label>

				<div class="col-sm-10 col-xs-12">
					<input type="text" class="form-control" name="position">
					<span class="help-block">微站导航菜单数量 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label star">模板缩略图</label>
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
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>

				<div class="col-sm-10 col-xs-12">
					<input name="method" type="hidden" value="download">
					<input name="token" type="hidden" value="6708fa25">
					<input type="submit" class="btn btn-primary" id="createBtn" name="submit" onclick="$(':hidden[name=method]').val('create');"
					       value="生成模板">
					<p class="help-block">点此直接在源码目录 theme<span class="identifie"></span> 处生成模板文件, 方便快速开发</p>
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
			msg += '模板名称不能为空 <br/>';
		}
		var names = $.trim($(':text[name="name"]').val());
		if (!/^[a-z]\w+$/i.test(names)) {
			msg += '模板标识必须以英文字母开始. 后跟英文,字母,数字或下划线<br/>';
		}
		var version = $.trim($(':text[name="version"]').val());
		if (!/^[\d\.]+$/i.test(version)) {
			msg += '请设置版本号. 版本号只能为数字或小数点<br/>';
		}
		var resume = $.trim($(':text[name="description"]').val());
		if (resume == '') {
			msg += '模板简述不能为空<br/>';
		}
		var author = $.trim($(':text[name="author"]').val());
		if (author == '') {
			msg += '作者不能为空<br/>';
		}
		var url = $.trim($(':text[name="url"]').val());
		if (url == '') {
			msg += '请输入发布url<br/>';
		}
		var thumb = $.trim($(':text[name="thumb"]').val());
		if (thumb == '') {
			msg += '模板缩略图不能为空<br/>';
		}
		var position = $.trim($(':text[name="position"]').val());
		if (!/^\d+$/.test(position)) {
			msg += '微站导航菜单数量必须为>=0的数字<br/>';
		}
		if (msg) {
			util.message(msg, '', 'error');
			return false;
		}
	});
</script>