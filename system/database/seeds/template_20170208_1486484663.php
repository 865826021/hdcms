<?php namespace system\database\seeds;

use houdunwang\database\build\Seeder;

class HdTemplate extends Seeder {
	//执行
	public function up() {
		$sql = <<<str
INSERT INTO `hd_template` (`tid`, `name`, `title`, `version`, `resume`, `author`, `url`, `industry`, `position`, `is_system`, `thumb`, `is_default`, `locality`, `module`)
VALUES
	(1,'default','默认模板','1.9','HDCMS 默认模板','后盾人','http://open.hdcms.com','other',10,1,'thumb.jpg',0,1,'article');
str;
		Db::execute( $sql );
	}

	//回滚
	public function down() {

	}
}