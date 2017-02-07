<?php namespace system\database\seeds;
use houdunwang\database\build\Seeder;
class user extends Seeder {
    //执行
	public function up() {
		$sql = <<<str
INSERT INTO `hd_user` (`uid`, `groupid`, `username`, `password`, `security`, `status`, `regtime`, `regip`, `lasttime`, `lastip`, `starttime`, `endtime`, `qq`, `mobile`, `email`, `mobile_valid`, `email_valid`, `remark`)
VALUES
	(1,0,'admin','','be7540ee9f',1,1465771582,'123.119.83.235',1486488154,'61.149.222.56',0,0,'0','','',0,0,'');
str;
		Db::execute( $sql );
    }
    //回滚
    public function down() {

    }
}