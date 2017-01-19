<?php namespace system\service\navigate;

use houdunwang\framework\build\Provider;

class NavigateProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Navigate', function ( $app ) {
			return new Navigate( $app );
		} );
	}
}