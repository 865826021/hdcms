<extend file="resource/view/system"/>
<block name="content">
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i></li>
		<li><a href="?s=system/manage/menu">系统</a></li>
		<li class="active">一键更新</li>
	</ol>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active"><a href="#">系统更新</a></li>
	</ul>
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-info">
				<div class="panel-body alert-info">
					<h5 style="margin-top: 0px;"><i class="fa fa-refresh"></i> <strong>更新日志</strong></h5>
					<p>
						<a href="#">HDCMS 2.0更新说明【2016年08月1日】</a>
						<span class="pull-right">2016-08-1</span>
					</p>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="panel panel-info">
				<div class="panel-body alert-info">
					<h5 style="margin-top: 0px;"><i class="fa fa-bullhorn"></i> <strong>系统公告</strong></h5>
					<p>
						<a href="">HDCMS 开发视频正在筹划中</a>
						<span class="pull-right">2016-08-2</span>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="form-horizontal">
		<div class="alert alert-danger">
			<i class="fa fa-exclamation-triangle"></i> 更新时请注意备份网站数据和相关数据库文件！官方不强制要求用户跟随官方意愿进行更新尝试！
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">发布日期</label>
			<div class="col-sm-10">
				<p class="form-control-static"><span class="fa fa-square-o"></span> &nbsp; 系统当前Release版本: Build {{$hdcms['releaseCode']}}</p>
				<div class="help-block text-danger">系统会检测当前程序文件的变动, 如果被病毒或木马非法篡改, 会自动警报并提示恢复至默认版本, 因此可能修订日期未更新而文件有变动</div>
			</div>
		</div>
	</div>
	<if value="$data['valid']==1">
		<form action="" class="form-horizontal">
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">更新版本</label>
				<div class="col-sm-10">
					<foreach from="$data['lists']" value="$f">
						<p class="form-control-static">
							<span class="fa fa-square-o"></span> &nbsp; HDCMS {{$f['versionCode']}} Release版本: Build {{$f['releaseCode']}}
							更新时间【{{date('Y年m月d日',$f['createtime'])}}】
						</p>
					</foreach>
				</div>
			</div>
			<if value="!empty($data['data']['files'])">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">文件列表</label>
					<div class="col-sm-10 ">
						<div class="alert alert-success form-control-static">
							<foreach from="$data['data']['files']" value="$f">
								{{preg_replace('/\s+/','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$f)}}<br/>
							</foreach>
						</div>
					</div>
				</div>
			</if>
			<if value="!empty($data['data']['sql'])">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">更新数据</label>
					<div class="col-sm-10 ">
						<div class="alert alert-success form-control-static">
							<foreach from="$data['data']['sql']" value="$f">
								{{$f}} <hr/>
							</foreach>
						</div>
					</div>
				</div>
			</if>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">更新协议</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" id="cp1"> 我已经做好了相关文件的备份工作
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="cp2"> 认同官方的更新行为并自愿承担更新所存在的风险
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="cp3"> 理解官方的辛勤劳动并报以感恩的心态点击更新按钮
						</label>
					</div>
				</div>
			</div>
			<button class="btn btn-primary" type="button" onclick="upgrade()">开始更新</button>
			<else/>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">更新日志</h3>
				</div>
				<div class="panel-body">
					{{$data['message']}}
				</div>
			</div>
		</form>
	</if>
</block>
<script>
	function upgrade() {
		require(['util'], function (util) {
			if ($("#cp1:checked").is(':checked') && $("#cp2:checked").is(':checked') && $("#cp3:checked").is(':checked')) {
				if (confirm('更新将直接覆盖本地文件, 请注意备份文件和数据. \n\n**另注意** 更新过程中不要关闭此浏览器窗口.')) {
					location.href = "{{u('upgrade',['action'=>'sql'])}}";
				}
			} else {
				util.message('请认真检查系统协议', '', 'error');
			}
		})
	}
</script>