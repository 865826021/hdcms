<?php namespace system\service\link;
use houdunwang\framework\build\Facade;

//外观构造类
class LinkFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Link';
	}
}