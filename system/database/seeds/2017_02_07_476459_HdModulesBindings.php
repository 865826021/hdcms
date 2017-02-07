<?php namespace system\database\seeds;

use houdunwang\database\build\Seeder;

class HdModulesBindings extends Seeder {
	//执行
	public function up() {
		$sql = <<<str
INSERT INTO `hd_modules_bindings` (`bid`, `module`, `entry`, `title`, `controller`, `do`, `url`, `icon`, `orderby`)
VALUES
	(1,'article','web','桌面入口导航','','web','','',0);
str;
		Db::execute( $sql );
	}

	//回滚
	public function down() {

	}
}