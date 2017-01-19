<?php namespace system\service\web;

use houdunwang\framework\build\Provider;

class WebProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->bind( 'Web', function ( $app ) {
			return new Web( $app );
		}, true );
	}
}