<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class migrations extends Migration {
    //执行
	public function up() {
		if ( Schema::tableExists( 'migrations' ) ) {
			return;
		}
		$sql = <<<sql
CREATE TABLE `hd_migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
sql;
		Db::execute( $sql );
    }

    //回滚
    public function down() {
    }
}