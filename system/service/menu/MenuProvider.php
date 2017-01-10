<?php namespace system\service\Menu;
use houdunwang\framework\build\Provider;

class MenuProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Menu', function ( $app ) {
			return new Menu($app);
		} );
	}
}