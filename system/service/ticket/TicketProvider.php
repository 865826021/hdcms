<?php namespace system\service\ticket;
use houdunwang\framework\build\Provider;

class TicketProvider extends Provider {
	//延迟加载
	public $defer = true;

	//服务运行时自动执行的方法
	public function boot() {
	}

	public function register() {
		$this->app->single( 'Ticket', function ( $app ) {
			return new Ticket($app);
		} );
	}
}