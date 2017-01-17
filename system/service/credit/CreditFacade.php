<?php namespace system\service\credit;
use houdunwang\framework\build\Facade;

//外观构造类
class CreditFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Credit';
	}
}