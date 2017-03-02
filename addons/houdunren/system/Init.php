<?php namespace addons\houdunren\system;

/**
 * 模块加载时自动执行类
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\HdCommon;

class Init extends HdCommon {
	//模块加载时自动执行的方法
	public function run() {
		c( 'oss.bucket', 'houdunren' );
		c('upload.size',100000000);
	}
}