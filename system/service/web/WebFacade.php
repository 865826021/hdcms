<?php namespace system\service\web;
use houdunwang\framework\build\Facade;

//外观构造类
class WebFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Web';
	}
}