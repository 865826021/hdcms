<?php namespace system\service\User;

use houdunwang\framework\build\Provider;

class UserProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->bind( 'User', function ( $app ) {
			return new User( $app );
		}, true );
	}
}