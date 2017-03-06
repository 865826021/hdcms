<?php namespace system\database\seeds;

use houdunwang\database\build\Seeder;

class config extends Seeder {
	//执行
	public function up() {
		$sql = <<<str
INSERT INTO `hd_config` (`site`, `register`)
VALUES
	('{\"is_open\":\"1\",\"close_message\":\"网站维护中,请稍候访问\",\"enable_code\":0,\"upload\":{\"size\":\"2000\",\"type\":\"jpg,jpeg,gif,png,zip,rar,doc,txt,pem,json,mp4\",\"path\":\"attachment\",\"mold\":\"oss\"},\"way\":\"oss\",\"http\":{\"rewrite\":\"1\"},\"app\":{\"debug\":\"1\"},\"oss\":{\"accessKeyId\":\"LTAIPSSlOmIqFeo3\",\"accessKeySecret\":\"VA5HVmet9ioRRIxs9muZ2qQrpD1c6M\",\"endpoint\":\"http://oss-cn-hangzhou.aliyuncs.com\",\"bucket\":\"houdunwang\",\"bucket_endpoint\":\"http://houdunren.oss-cn-hangzhou.aliyuncs.com\"}}','{\"is_open\":\"1\",\"audit\":\"0\",\"enable_code\":\"0\",\"groupid\":\"1\"}');
str;
		Db::execute( $sql );
	}

	//回滚
	public function down() {

	}
}