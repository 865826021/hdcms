<?php namespace system\service\package;
use houdunwang\framework\build\Provider;

class PackageProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Package', function ( $app ) {
			return new Package($app);
		} );
	}
}