<?php namespace system\service\Wx;
use houdunwang\framework\build\Provider;

class WxProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Wx', function ( $app ) {
			return new Wx($app);
		} );
	}
}