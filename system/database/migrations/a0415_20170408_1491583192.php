<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class a0415 extends Migration {
    //执行
	public function up() {
		Schema::table('modules_bindings', function (Blueprint $table) {
			//修改字段
			$table->string('json', 2000)->comment('数据')->add();
		});
    }

    //回滚
    public function down() {

    }
}