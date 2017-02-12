<?php namespace system\service\link;
use houdunwang\framework\build\Provider;

class LinkProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Link', function ( $app ) {
			return new Link($app);
		} );
	}
}