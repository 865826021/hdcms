<?php namespace system\service\member;
use houdunwang\framework\build\Provider;

class MemberProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Member', function ( $app ) {
			return new Member($app);
		} );
	}
}