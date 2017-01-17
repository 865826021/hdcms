<?php namespace system\service\credit;
use houdunwang\framework\build\Provider;

class CreditProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Credit', function ( $app ) {
			return new Credit($app);
		} );
	}
}