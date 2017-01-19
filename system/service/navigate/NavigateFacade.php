<?php namespace system\service\navigate;
use houdunwang\framework\build\Facade;

//外观构造类
class NavigateFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Navigate';
	}
}