<?php namespace system\database\migrations;
use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;

class site_setting_add_field_register_option extends Migration {
    //执行
	public function up() {
		Schema::table( 'site_setting', function ( Blueprint $table ) {
			$table->string('register_option',3000)->add();
		});
    }

    //回滚
    public function down() {

    }
}