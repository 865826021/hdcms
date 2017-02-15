<?php namespace system\service\pay;

use houdunwang\framework\build\Facade;

//外观构造类
class PayFacade extends Facade {

	public static function getFacadeAccessor() {
		return 'Pay';
	}
}