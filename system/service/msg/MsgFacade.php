<?php namespace system\service\msg;

use houdunwang\framework\build\Facade;

class MsgFacade extends Facade {

	public static function getFacadeAccessor() {
		return 'Msg';
	}
}