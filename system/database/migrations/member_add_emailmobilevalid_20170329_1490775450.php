<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class member_add_emailmobilevalid extends Migration {
    //执行
	public function up() {
		Schema::table('member', function (Blueprint $table) {
			//邮箱验证
			$table->tinyInteger('email_valid', 1)->add();
			//手机号验证
			$table->tinyInteger('mobile_valid', 1)->add();
		});
    }

    //回滚
    public function down() {

    }
}