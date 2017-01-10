<?php namespace system\service\Menu;
use houdunwang\framework\build\Facade;

//外观构造类
class MenuFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Menu';
	}
}