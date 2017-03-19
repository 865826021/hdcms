<?php namespace system\database\seeds;

use houdunwang\database\build\Seeder;

class user_group extends Seeder {
	//执行
	public function up() {
		$sql = <<<str
INSERT INTO `hd_user_group` (`id`, `name`, `maxsite`, `allfilesize`, `daylimit`, `package`, `system_group`, `router_num`, `middleware_num`)
VALUES
	(1,'体验组',1,0,30,'\"\"',1,1,2);
str;
		Db::execute( $sql );
	}

	//回滚
	public function down() {

	}
}