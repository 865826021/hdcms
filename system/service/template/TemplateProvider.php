<?php namespace system\service\template;
use houdunwang\framework\build\Provider;

class TemplateProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Template', function ( $app ) {
			return new Template($app);
		} );
	}
}