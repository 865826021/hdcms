<?php namespace system\service\pay;

use houdunwang\framework\build\Provider;

class PayProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Pay', function ( $app ) {
			return new Pay( $app );
		} );
	}
}