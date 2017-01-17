<?php namespace system\service\module;
use houdunwang\framework\build\Provider;

class ModuleProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Module', function ( $app ) {
			return new Module($app);
		} );
	}
}