<?php namespace system\database\seeds;

use houdunwang\database\build\Seeder;

class menu_add_mobile extends Seeder {
	//执行
	public function up() {
		$sql = <<<str
INSERT INTO `hd_menu` ( `pid`, `title`, `permission`, `url`, `append_url`, `icon`, `orderby`, `is_display`, `is_system`, `mark`)
VALUES
	(66,'短信通知设置','feature_setting_mobile','?s=site/setting/mobile','','fa fa-cubes',0,1,1,'feature');
str;
		Db::execute( $sql );
	}

	//回滚
	public function down() {

	}
}