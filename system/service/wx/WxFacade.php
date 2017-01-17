<?php namespace system\service\wx;
use houdunwang\framework\build\Facade;

//外观构造类
class WxFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Wx';
	}
}