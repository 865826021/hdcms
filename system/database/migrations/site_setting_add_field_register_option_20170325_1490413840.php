<?php namespace system\database\migrations;

use houdunwang\database\build\Migration;
use houdunwang\database\build\Blueprint;
use houdunwang\database\Schema;

class site_setting_add_field_register_option extends Migration {
	//执行
	public function up() {
		Schema::table( 'site_setting', function ( Blueprint $table ) {
			$table->string( 'register_option', 3000 )->comment( '会员注册选项' )->add();
		} );
	}

	//回滚
	public function down() {
		Schema::table( 'site_setting', function ( Blueprint $table ) {
			$table->dropField( 'register_option' );
		} );
	}
}