<?php namespace system\database\seeds;
use houdunwang\database\build\Seeder;
class config extends Seeder {
    //执行
	public function up() {
		$sql=<<<str
INSERT INTO `hd_config` (`id`, `site`, `register`)
VALUES
	(1,'{\"is_open\":\"1\",\"enable_code\":\"0\",\"close_message\":\"网站维护中,请稍候访问\",\"upload\":{\"size\":\"200000\",\"type\":\"jpg,jpeg,gif,png,zip,rar,doc,txt,pem,json\"},\"debug\":\"0\",\"app\":{\"debug\":\"0\"},\"http\":{\"rewrite\":\"0\"}}','{\"is_open\":\"1\",\"audit\":\"0\",\"enable_code\":\"0\",\"groupid\":\"1\"}');
str;
		Db::execute($sql);
    }
    //回滚
    public function down() {

    }
}