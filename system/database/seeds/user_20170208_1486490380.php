<?php namespace system\database\seeds;
use houdunwang\database\build\Seeder;
class user extends Seeder {
    //执行
	public function up() {
		$sql = <<<str
INSERT INTO `hd_user` (`uid`, `groupid`, `username`, `password`, `security`, `status`, `regtime`, `regip`, `lasttime`, `lastip`, `starttime`, `endtime`, `qq`, `mobile`, `email`, `mobile_valid`, `email_valid`, `remark`)
VALUES
	(1,0,'admin','','7b46c62725',1,1465771582,'123.119.83.235',1487554341,'121.69.37.186',0,0,'232323','','',0,0,'');
str;
		Db::execute( $sql );
    }
    //回滚
    public function down() {

    }
}