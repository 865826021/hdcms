<?php namespace system\service\site;
use houdunwang\framework\build\Provider;

class SiteProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Site', function ( $app ) {
			return new Site($app);
		} );
	}
}