<?php namespace system\service\Site;
use houdunwang\framework\build\Facade;

//外观构造类
class SiteFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Site';
	}
}