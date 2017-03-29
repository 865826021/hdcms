<?php namespace system\service\msg;

use houdunwang\framework\build\Provider;

class MsgProvider extends Provider {
	public $defer = true;

	public function boot() {
	}

	public function register() {
		$this->app->single( 'Msg', function ( $app ) {
			return new Msg( $app );
		} );
	}
}