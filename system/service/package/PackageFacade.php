<?php namespace system\service\package;
use houdunwang\framework\build\Facade;

//外观构造类
class PackageFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Package';
	}
}