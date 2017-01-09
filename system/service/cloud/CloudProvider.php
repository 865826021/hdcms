<?php namespace system\service\Cloud;
use houdunwang\framework\build\Provider;

class CloudProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Cloud', function ( $app ) {
			return new Cloud($app);
		} );
	}
}