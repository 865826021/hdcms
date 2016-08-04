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
		<div class="col-sm-6" style="padding-left: 0px;">
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
		<div class="col-sm-6" style="padding: 0px;">
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
	<if value="$data['valid']==1">
		<div class="alert alert-danger">
			<i class="fa fa-exclamation-triangle"></i> 更新时请注意备份网站数据和相关数据库文件！官方不强制要求用户跟随官方意愿进行更新尝试！
		</div>
		<form action="" class="form-horizontal">
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">发布日期</label>
				<div class="col-sm-10">
					<p class="form-control-static"><span class="fa fa-square-o"></span> &nbsp; 系统当前Release版本: Build {{$hdcms['releaseCode']}}</p>
					<div class="help-block">系统会检测当前程序文件的变动, 如果被病毒或木马非法篡改, 会自动警报并提示恢复至默认版本, 因此可能修订日期未更新而文件有变动</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					本次更新的版本
				</div>
				<div class="panel-body">
					<p style="margin: 0px;">
						<foreach from="$data['lists']" value="$f">
							<span class="text-info"> HDCMS {{$f['versionCode']}} Release版本: Build {{$f['releaseCode']}} 更新时间【{{date('Y年m月d日',$f['createtime'])}}】 </span> <br/>
						</foreach>
					</p>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					影响的文件列表
				</div>
				<div class="panel-body">
					<p style="margin: 0px;">
						<foreach from="$data['data']['files']" value="$f">
							<span class="text-info"> {{preg_replace('/\s+/','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$f)}} </span> <br/>
						</foreach>
					</p>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					数据表
				</div>
				<div class="panel-body">
					<p style="margin: 0px;">
						<foreach from="$data['data']['tables']" value="$f">
							<span class="text-info">[{{$f['do']}}]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;table:{{$f['table']}} </span> <br/>
						</foreach>
					</p>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					修改的字段
				</div>
				<div class="panel-body">
					<p style="margin: 0px;">
						<foreach from="$data['data']['fields']" value="$f">
							<span class="text-info">[{{$f['do']}}]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;table:{{$f['table']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;fields::{{$f['field']}} </span>
							<br/>
						</foreach>
					</p>
				</div>
			</div>
			<button class="btn btn-primary" type="button" ng-click="download()" ng-if="!alldown">开始更新</button>
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